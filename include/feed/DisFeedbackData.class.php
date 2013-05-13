<?php
/**
 * @package: DIS.DATA
 * @file   : DisFeedbackData.class.php
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisFeedbackData extends DisDBStaticTable
{
    static function insert($type, $user_id, $content = '', $email = '')
    {
//        $str = "insert into user_feedbacks (`type`, email, user_id, content)
//            values ('$type', '$email', $user_id, '$content')";
        $str = "insert into user_feedbacks (`type`, user_id, content)
            values ('$type', $user_id, '#$email#-$content')";
        return parent::insert($str);
    }

    static function list_applies($page = 0, $count = 40)
    {
        $str = "select * from user_feedbacks
            where `type` = 'apply' order by id desc limit " . $page * $count . ", $count";
        return DisDBTable::load_datas($str);
    }

    static function list_feedbacks($page = 0, $count = 40)
    {
        $str = "select * from user_feedbacks
            where `type` = 'feedback' order by id desc limit " . $page * $count . ", $count";
        return DisDBTable::load_datas($str);
    }

    static function cancel($id)
    {
        $str = "update user_feedbacks set status = 'cancel' where ID = $id";
        return DisDBTable::query($str);
    }

    static function success($id)
    {
        $str = "update user_feedbacks set status = 'success' where ID = $id";
        return DisDBTable::query($str);
    }

    static function doing($id)
    {
        $str = "update user_feedbacks set status = 'doing' where ID = $id";
        return DisDBTable::query($str);
    }

    static function delete($id)
    {
        return parent::delete($id, 'user_feedbacks');
    }
}
?>