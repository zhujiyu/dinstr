<?php
/**
 * @package: DIS.DATA
 * @file   : DisHeadUserData.class.php
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisHeadUserData extends DisDBStaticTable
{
    static function load($approve_id)
    {
        $str = "select * from head_users where ID = $approve_id";
        return DisDBTable::load_line_data($str);
    }

    static function insert($head_id, $user_id)
    {
        $str = "insert into head_users (head_id, user_id)
            values ($head_id, $user_id)";
        return parent::insert($str);
    }

    static function approve($id)
    {
        $str = "update head_users set approve = 1 where ID = $id";
        return DisDBTable::query($str);
    }

    static function cancel_approve($id)
    {
        $str = "update head_users set approve = 0 where ID = $id";
        return DisDBTable::query($str);
    }

    static function remove($head_id, $user_id)
    {
        $str = "delete from head_users
            where user_id = $user_id and head_id = $head_id";
        return DisDBTable::query($str);
    }

    static function get_interest_id($head_id, $user_id)
    {
        $str = "select ID from head_users
            where user_id = $user_id and head_id = $head_id";
        return parent::get_id($str);
    }

    static function exist($head_id, $user_id)
    {
        $str = "from head_users
            where user_id = $user_id and head_id = $head_id";
        return DisDBTable::count($str) > 0;
    }

    static function list_follow_user_ids($head_id)
    {
        $str = "select user_id from head_users
            where head_id = $head_id order by user_id";
        return DisDBTable::load_datas($str);
    }

    static function list_interest_head_ids($user_id, $max_id = 0, $count = 20)
    {
        if( $max_id > 0 )
            $whr = "and head_id < $max_id";
        else
            $whr = "";

        $str = "select head_id from head_users
            where user_id = $user_id $whr
            order by head_id desc  limit $count";
        return DisDBTable::load_datas($str);
    }

    static function list_approve_head_ids($user_id, $max_id = 0, $count = 20)
    {
        if( $max_id > 0 )
            $whr = "and head_id < $max_id";
        else
            $whr = "";

        $str = "select head_id from head_users
            where user_id = $user_id $whr and approve > 0
            order by head_id desc limit $count";
        return DisDBTable::load_datas($str);
    }

    static function list_approve_user_ids($head_id)
    {
        $str = "select user_id from head_users
            where head_id = $head_id and approve > 0 order by user_id";
        return DisDBTable::load_datas($str);
    }

    static function delete($id)
    {
        return parent::delete($id, 'head_users');
    }
}
?>