<?php
/**
 * @package: DIS.DATA
 * @file   : DisInviteData.class.php
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisInviteData extends DisDBStaticTable
{
    static function insert($user_id, $salt, $code, $email = '', $new_uid = 0)
    {
        $str = "insert into user_invites (user_id, new_uid, email, salt, code)
            values ($user_id, $new_uid, '$email', '$salt', '$code')";
        return parent::insert($str);
    }

    static function list_invite_users($user_id)
    {
        $str = "select * from user_invites where user_id = $user_id order by id desc";
        return DisDBTable::load_datas($str);
    }

    static function load($code)
    {
        $str = "select * from user_invites where code = '$code'";
        return DisDBTable::load_line_data($str);
    }

    static function update($id, $new_uid)
    {
        $str = "update user_invites set new_uid = $new_uid where ID = $id";
        return DisDBTable::query($str);
    }

    static function exist($email)
    {
        $str = "select * from user_invites where email = '$email'";
        return parent::exist($str);
    }

    static function delete($id)
    {
        return parent::delete($id, 'user_invites');
    }
}
?>