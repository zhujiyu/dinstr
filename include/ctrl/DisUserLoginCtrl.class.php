<?php
/**
 * @package: DIS.CTRL
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisUserLoginCtrl extends DisUserLoginData
{
    function  __construct($login_id = 0)
    {
        parent::__construct($login_id);
    }

    static function set_inline($user_id)
    {
        $ur = DisUserDataCache::get_last_inline($user_id);
        if( $ur && time() - $ur < 900 )
            return;
        $login_id = DisUserDataCache::get_login_id($user_id);

        if( !$login_id || !$ur )
        {
            $login = new DisUserLoginCtrl();
            $login->insert($user_id);
            DisUserDataCache::set_last_inline($user_id);
            DisUserDataCache::set_login_id($user_id, $login->ID);
        }
        else
        {
            $login = new DisUserLoginCtrl($login_id);
            $login->checkin();
            DisUserDataCache::set_last_inline($user_id);

            $param = new DisUserParamCtrl($user_id);
            $param->increase('online_times', time() - $ur);
        }
    }

    static function check_inline($user_id)
    {
        $ur = DisUserDataCache::get_last_inline($user_id);
        if( $ur && time() - $ur < 360 )
            return true;
        return false;
    }

    /**用户登录
     * @param string $name 用户名/邮箱/用户ID
     * @param string $pwrd 密码
     * @return boolen 成功返回 pmCtrlUser 用户对象
     */
    function login($name, $pwrd)
    {
        if( !$name || !$pwrd )
            throw new DisException('用户名不存在');

        $user = new DisUserCtrl($name);
        if( !$user->ID )
            throw new DisParamException('用户名不存在');
        if( !$user->check_password($pwrd) )
            throw new DisParamException('密码错误');
        DisUserLoginCtrl::set_inline($user->ID);

        return $user;
    }

    function logout()
    {
        parent::checkin();
        DisUserDataCache::set_last_inline($this->user_id, -1);
    }
}

//        $this->insert($user->ID);
//        DisUserDataCache::set_last_inline($this->user_id);
//        DisUserDataCache::set_login_id($user->ID, $this->ID);

//        $in_time = time() - strtotime($login->attr('logout'));
//        if( $in_time > 300 )
//        {
//            $login->checkin();
//            $param = new DisUserParamCtrl($login->user_id);
//            $param->increase('online_times', $in_time);
//        }
//
//        DisUserDataCache::set_last_inline($user_id);
//
//        if( $login->ID )
//        {
//            $in_time = time() - strtotime($login->attr('logout'));
//            if( $in_time > 300 )
//            {
//                $login->checkin();
//                $param = new DisUserParamCtrl($user_id);
////                $param->ID = $user_id;
//                $param->increase('online_times', $in_time);
//            }
//        }
//
//        DisUserDataCache::set_last_inline($user_id);

//        DisObject::print_array($user);
//        $user->init($name);
//        $r = $user->check_password($pwrd);
//        if( !$r )
//            throw new DisException('密码错误');

?>
