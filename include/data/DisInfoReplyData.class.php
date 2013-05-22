<?php
/**
 * @package: DIS.DATA
 * @file   : DisInfoReplyData.class.php
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

class DisInfoReplyData extends DisObject
{
    static function insert($note_id, $user_id)
    {
        $str = "insert into info_replies (note_id, user_id) values ($note_id, $user_id)";
        return DisDBTable::check_query($str, 1);
    }

    static function load_reply_mails($user_id, $max_id = 0, $count = 20)
    {
        $whr = $max_id > 0 ? " and note_id < $max_id " : "";
        $str = "select note_id from info_replies
            where user_id = $user_id $whr
            order by note_id desc limit $count";
//            order by note_id desc limit " . $page * $size . ", $count";
        return DisDBTable::load_datas($str);
    }
}
?>