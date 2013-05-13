<?php
/**
 * @package: DIS.DATA
 * @file   : pmDataFeed.php
 * @abstract  : 数据库层的Feed算法 这里是简单的算法
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisFeedData extends DisObject
{
    var $user_id = 0;
    var $table;

    function  __construct($user_id)
    {
        $this->table = "info_feeds";
        $this->user_id = $user_id;
    }

    function get_max_feed()
    {
        $str = "select id, user_id, flow_id, flow_time
            from $this->table
            where user_id = $this->user_id
            order by flow_id desc limit 1";
        return DisDBTable::load_line_data($str);
    }

    function get_min_feed()
    {
        $str = "select id, user_id, flow_id, flow_time
            from $this->table
            where user_id = $this->user_id
            order by flow_id asc limit 1";
        return DisDBTable::load_line_data($str);
    }

    /**
     * 删除过期的信息数据
     * @param integer $time 过期的时间（含）
     * @return integer 删除的行数
     */
    function drop_outtime($time)
    {
        $str = "delete from $this->table
                where  user_id = $this->user_id and flow_time <= from_unixtime($time)";
        return DisDBTable::query($str);
    }

    function drop_flow($flow_id)
    {
        $str = "delete from $this->table where  ID = $flow_id";
        return DisDBTable::query($str);
    }

    /**
     * 插入一条用户追踪的信息
     * @param integer $flow_id mail_flows表的ID
     * @param timestamp $flow_time 生成信息流的时间
     * @return boolen 是否插入成功
     */
    function insert($flow_id, $flow_time)
    {
        if( !$flow_id || !$this->user_id )
            throw new DisParamException("参数不合法！");
        $str = "insert into $this->table (flow_id, user_id, flow_time)
                values ($flow_id, $this->user_id, unix_timestamp('$flow_time'))";
        $r = DisDBTable::query($str) == 1;
        return $r;
//        return (pmDBTable::query($str) == 1);
    }

    /**
     * 开始从数据库读取信息流数据，批量插入到追踪表中
     * @param integer $start 之前读取的最后时间
     * @param integer $end 结束时间
     * @param integer $count 一次读取的信息数
     * @return integer 返回插入的行数
     */
    protected function feed_hist_mails($start, $end, $count)
    {
        $str = "insert into $this->table (flow_id, user_id, flow_time)
            select f.ID as flow_id, cu.user_id, unix_timestamp(f.flow_time) as flow_time
            from mail_flows as f, channel_users as cu
            where cu.user_id = $this->user_id and f.channel_id = cu.channel_id
                and flow_time > from_unixtime($start) and flow_time < from_unixtime($end)
            order by flow_time desc limit $count";
        return DisDBTable::query($str);
    }

    protected function top_follows($count = 20)
    {
        $str = "select ID, user_id, flow_id, flow_time
            from $this->table
            where user_id = $this->user_id
            order by flow_id desc limit $count";
        return DisDBTable::load_datas($str);
    }

    protected function list_follows($last_flow, $count = 20)
    {
        $str = "select ID, user_id, flow_id, flow_time
            from $this->table
            where user_id = $this->user_id and flow_id < $last_flow
            order by flow_id desc limit $count";
        return DisDBTable::load_datas($str);
    }

    protected function load_flows($start, $end)
    {
        $str = "select ID, user_id, channel_id, mail_id, flow_time
            from mail_flows
            where flow_time >= from_unixtime($start) and flow_time < from_unixtime($end)
            order by channel_id";
        return DisDBTable::load_datas($str);
    }

    function subscribe($channel_id, $start, $end)
    {
        $str = "insert into $this->table (flow_id, user_id, flow_time)
            select ID as flow_id, $this->user_id as user_id, unix_timestamp(flow_time) as flow_time
            from mail_flows
            where channel_id = $channel_id and flow_time > from_unixtime($start)
                and flow_time <= from_unixtime($end)";
        return DisDBTable::query($str);
    }

    function cancel_subscribe($channel_id)
    {
        $str = "delete from feed
            using $this->table as feed left join mail_flows as flow on flow.ID = feed.flow_id
            where feed.user_id = $this->user_id and flow.channel_id = $channel_id";
        return DisDBTable::query($str);
    }
}

/**
 * Feed 2.0 * 分区Feed算法：
 *
 * 一、数据结构
 * a、将用户和网寨进行随机分区，将用户关系和用户订阅网寨关系根据被关注者和网摘所属分区进行拆分
 *    用户加入网寨关系不分区寨分；
 * b、建立分区的最新投稿/发布表；
 * c、对用户关注的用户，订阅的网寨，建立单独的索引表；
 *
 * 二、Feed核心
 * a、微博模式：对用户关注的每个用户分区的分区最新发布表，进行简单Feed运算，
 *    将用户关注对象的所有发布动态插入个人关注列表中；
 * b、订阅模式：订阅模式是微博模式的改变主体的翻版；
 * c、投稿模式：最新投稿表根据投稿用户进行分区拆分，建立分区最新投稿表A，
 *    然后，第一步根据微博模式产生用户所有关注对象的投稿表B，
 *    第二步，将表B和网寨会员表拼接产生最终的用户关注的投稿信息。
 *
 * 三、分区的拆分和合并算法
 * a、分区拆分是一个分区过大时，拆分成两个，
 *    拆分时，将原分区最新动态表转入到新的分区最新动态表中，使得用户感觉不到分区的变化；
 * b、分区合并是将两个分区合并成一个，
 *    合并时，将原来的两个分区的最新动态表合并到一张表里。
 *
 * 关键字： 随机分区 分区索引 分区用户关系 分区网寨订阅 分区最新投稿/发布表
 *         微博模式 分区拆分 分区合并
 *
 * Feed 2.0 * 分区Feed算法：
 *
 * 一、数据结构
 * a、将用户和网寨进行随机分区，将用户关系和用户订阅网寨关系根据被关注者和网摘所属分区进行拆分
 *    用户加入网寨关系不分区寨分；
 * b、建立分区的最新投稿/发布表；
 * c、对用户关注的用户，订阅的网寨，建立单独的索引表；
 *
 * 二、Feed核心
 * a、微博模式：对用户关注的每个用户分区的分区最新发布表，进行简单Feed运算，
 *    将用户关注对象的所有发布动态插入个人关注列表中；
 * b、订阅模式：订阅模式是微博模式的改变主体的翻版；
 * c、投稿模式：最新投稿表根据投稿用户进行分区拆分，建立分区最新投稿表A，
 *    然后，第一步根据微博模式产生用户所有关注对象的投稿表B，
 *    第二步，将表B和网寨会员表拼接产生最终的用户关注的投稿信息。
 *
 * 三、分区的拆分和合并算法
 * a、分区拆分是一个分区过大时，拆分成两个，
 *    拆分时，将原分区最新动态表转入到新的分区最新动态表中，使得用户感觉不到分区的变化；
 * b、分区合并是将两个分区合并成一个，
 *    合并时，将原来的两个分区的最新动态表合并到一张表里。
 *
 * 关键字： 随机分区 分区索引 分区用户关系 分区网寨订阅 分区最新投稿/发布表
 *         微博模式 分区拆分 分区合并
 */
?>