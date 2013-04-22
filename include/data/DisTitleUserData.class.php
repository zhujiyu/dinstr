<?php
/**
 * @package: DIS.DATA
 * @file   : DisTitleUserData.class.php
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisTitleUserData extends DisDBStaticTable
{
    static function load($approve_id)
    {
        $str = "select * from theme_users where ID = $approve_id";
        return DisDBTable::load_line_data($str);
    }

    static function insert($theme_id, $user_id)
    {
        $str = "insert into theme_users (theme_id, user_id) values ($theme_id, $user_id)";
        return parent::insert($str);
    }

    static function approve($id)
    {
        $str = "update theme_users set approve = 1 where ID = $id";
        return DisDBTable::query($str);
    }

    static function cancel_approve($id)
    {
        $str = "update theme_users set approve = 0 where ID = $id";
        return DisDBTable::query($str);
    }

    static function remove($theme_id, $user_id)
    {
        $str = "delete from theme_users where user_id = $user_id and theme_id = $theme_id";
        return DisDBTable::query($str);
    }

    static function get_interest_id($theme_id, $user_id)
    {
        $str = "select ID from theme_users
            where user_id = $user_id and theme_id = $theme_id";
        return parent::get_id($str);
    }

    static function exist($theme_id, $user_id)
    {
        $str = "from theme_users where user_id = $user_id and theme_id = $theme_id";
        return DisDBTable::count($str) > 0;
    }

    static function list_follow_user_ids($theme_id)
    {
        $str = "select user_id from theme_users where theme_id = $theme_id order by user_id";
        return DisDBTable::load_datas($str);
    }

    static function list_interest_theme_ids($user_id, $max_id = 0, $count = 20)
    {
        if( $max_id > 0 )
            $whr = "and theme_id < $max_id";
        else
            $whr = "";

        $str = "select theme_id from theme_users
            where user_id = $user_id $whr
            order by theme_id desc  limit $count";
        return DisDBTable::load_datas($str);
    }

    static function list_approve_theme_ids($user_id, $max_id = 0, $count = 20)
    {
        if( $max_id > 0 )
            $whr = "and theme_id < $max_id";
        else
            $whr = "";

        $str = "select theme_id from theme_users
            where user_id = $user_id $whr and approve > 0
            order by theme_id desc limit $count";
        return DisDBTable::load_datas($str);
    }

    static function list_approve_user_ids($theme_id)
    {
        $str = "select user_id from theme_users where theme_id = $theme_id and approve > 0 order by user_id";
        return DisDBTable::load_datas($str);
    }

    static function delete($id)
    {
        return parent::delete($id, 'theme_users');
    }
}
?>