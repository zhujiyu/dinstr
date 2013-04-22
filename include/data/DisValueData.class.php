<?php
/**
 * @package: DIS.DATA
 * @file   : DisValueData.class.php
 * @abstract  : 用户接口
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisValueData extends DisObject
{
    static function load_value_mails($channel_id, $start, $end)
    {
        $str = "select ID, user_id, channel_id, mail_id, flow_time, weight
            from mail_flows
            where channel_id = $channel_id -- and weight > 0
                and flow_time >= from_unixtime($start) and flow_time < from_unixtime($end)
            order by weight desc limit 100";
        return DisDBTable::load_datas($str);
    }
}
?>