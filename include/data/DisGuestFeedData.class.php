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

class DisGuestFeedData extends DisObject
{
    function load($chan_id, $start, $end)
    {
        $str = "select ID as flow_id, unix_timestamp(flow_time) as flow_time
            from mail_flows
            where channel_id = $chan_id
                and flow_time > from_unixtime($start) and flow_time < from_unixtime($end)
            order by flow_time desc";
        return DisDBTable::load_datas($str);
    }
}
?>
