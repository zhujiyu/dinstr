<?php
/**
 * @package: DIS.DATA
 * @file   : DisStreamData.class.php
 * @abstract  : 信息结点
 *
 * 各个函数的参数检查不严格，不做数据完整性一致性检查，甚至数据格式的检查也不完整
 * 上层调用的时候，自己保证数据
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisStreamData extends DisDBTable
{
    protected static $stable = "streams";

    // 构造函数
    function __construct($id = 0)
    {
        $this->table = 'streams';
        parent::__construct($id);
    }

    function insert($user_id, $note_id, $channel_id, $weight = 0)
    {
        if( !$note_id || !$user_id )
            throw new DisParamException("参数不合法！");
        $str = "insert into $this->table (user_id, note_id, channel_id, weight)
            values ($user_id, $note_id, $channel_id, $weight)";
        if( parent::query($str) != 1 )
            throw new DisDBException("插入信息流数据失败！");
        return parent::last_insert_Id();
    }

    static function load_flow_note($flow_id)
    {
        $str = "select ID, user_id, note_id, channel_id, weight, flow_time
            from ".DisStreamData::$stable." where ID = $flow_id";
        return parent::load_line_data($str);
    }

    static function top_channel_all_flows($channel_id, $count = 20)
    {
        $str = "select ID, user_id, note_id, channel_id, flow_time
            from ".DisStreamData::$stable."
            where channel_id = $channel_id
            order by ID desc limit $count";
        return parent::load_datas($str);
    }

    static function list_channel_all_flows($channel_id, $max_id = 0, $count = 20)
    {
        $str = "select ID, user_id, note_id, channel_id, flow_time
            from ".DisStreamData::$stable."
            where channel_id = $channel_id and ID < $max_id
            order by ID desc limit $count";
        return parent::load_datas($str);
    }

    static function load_value_mails($channel_id, $start, $end)
    {
        $str = "select ID, user_id, note_id, flow_time, weight
            from ".DisStreamData::$stable."
            where channel_id = $channel_id
                and flow_time >= from_unixtime($start) and flow_time < from_unixtime($end)
            order by weight desc limit 200";
        return parent::load_datas($str);
    }
}
?>