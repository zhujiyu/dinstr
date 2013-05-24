<?php
/**
 * @file : user.inc.php
 * @abstract 用户视图
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-05-23
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

function _login($name, $pwrd, $auto = null)
{
    if( !$name || !$pwrd )
        return false;

    try
    {
        $login = new DisUserLoginCtrl();
        $user = $login->login($name, $pwrd);
    }
    catch (DisParamException $ex)
    {
        return false;
    }

    if( $user->ID )
    {
        $_SESSION['userId'] = $user->ID;
        DisUserLoginCtrl::set_inline($user->ID);

        if( isset($auto) && $auto == 'on' )
            setcookie('dis-login', serialize(array('ID'=>$user->ID, 'pwrd'=>$pwrd)), time() + 168 * 3600);
        return true;
    }

    return false;
}

//        if( isset($_POST['autologin']) && $_POST['autologin'] == 'on' )
//        {
//            setcookie('pm-login', serialize(array('ID'=>$user->ID, 'pwrd'=>$pwrd)),
//                        time() + 168 * 3600);
//        }

?>