<?php
/**
 * @package: DIS.DATA
 * @file   : DisNotePhotoData.class.php
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

class DisNoteReplyData extends DisObject
{
    static function insert($mail_id, $user_id)
    {
        $str = "insert into mail_replies (mail_id, user_id) values ($mail_id, $user_id)";
        return DisDBTable::query($str) == 1;
    }

    static function load_reply_mails($user_id, $max_id = 0, $count = 20)
    {
        $whr = $max_id > 0 ? " and mail_id < $max_id " : "";
        $str = "select mail_id from mail_replies
            where user_id = $user_id $whr
            order by mail_id desc limit $count";
//            order by mail_id desc limit " . $page * $size . ", $count";
        return DisDBTable::load_datas($str);
    }
}
?>