<?php
/**
 * @package: DIS.Ctrl
 * @file   : DisInviteCtrl.class.php
 * @abstract  :
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisInviteCtrl extends DisInviteData
{
    static function invite_code($user_id, $email = '')
    {
        $salt = substr(md5(time().rand(1000, 10000)), 0, 8);
        $veri = substr(md5($user_id), 0, 4);
        $code = substr(md5($veri.$salt), 0, 8);

        while( parent::load($code) )
        {
            $salt = substr(md5(time().rand(1000, 10000)), 0, 8);
            $veri = substr(md5($user_id), 0, 4);
            $code = substr(md5($veri.$salt), 0, 8);
        }

        parent::insert($user_id, $salt, $code, $email);
        return $code.$veri;
    }

    static function check($invi)
    {
        $code = substr($invi, 0, 8);
        $veri = substr($invi, 8, 4);
        $invite = soInviteControl::load_invite($code);

        if( !$invite )
            throw new soException('邀请码不存在！');
        if( $invite['new_uid'] > 0 )
            throw new soException('该邀请码已经被使用过了。');

        $salt = $invite['salt'];
        $user_id = $invite['user_id'];
        if( $veri != substr(md5($user_id), 0, 4) || $code != substr(md5($veri.$salt), 0, 8) )
            throw new soException('邀请码错误！');
        return $invite;
    }

    static function list_invite_users($user_id)
    {
        $invites = parent::list_invite_users($user_id);
        $user = DisUserCtrl::user($user_id);

        for( $i = 0, $len = count($invites); $i < $len; $i ++ )
        {
            if( $invites[$i]['new_uid'] == 0 )
                continue;
            $invites[$i]['invite_user'] = DisUserCtrl::get_user_view($invites[$i]['new_uid']);
            $invites[$i]['invite_user']['followed'] = $user->check_follow($invites[$i]['new_uid']);
        }

        return $invites;
    }
}
?>