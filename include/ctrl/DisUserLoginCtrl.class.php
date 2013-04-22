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
    function  __construct($user_id = 0)
    {
        parent::__construct($user_id);
    }

    /**
     * 用户登录
     * @param string $name 用户名/邮箱/用户ID
     * @param string $pwrd 密码
     * @return boolen 成功返回 pmCtrlUser 用户对象
     */
    function login($name, $pwrd)
    {
        if( !$name || !$pwrd )
            throw new DisException('用户名不存在');

        $user = new DisUserCtrl();
        $user->init($name);
        if( !$user->ID )
            throw new DisException('用户名不存在');
        $r = $user->check_password($pwrd);
        if( !$r )
            throw new DisException('密码错误');

        $this->insert($user->ID);
        return $user;
    }

    function logout()
    {
        parent::checkin();
        DisUserDataCache::set_last_inline($this->user_id, -1);
    }
}
?>
