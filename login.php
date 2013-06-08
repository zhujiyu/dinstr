<?php
/**
 * DINSTR项目 PHP文件 v1.0.0
 * @package: DIS.PAGE
 * @file   : login.php
 * 用户登录页面
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 DINSTR(有向信息流)
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
require_once 'common.inc.php';
require_once 'view/user.inc.php';

$gSmarty = init_smarty();
$p = $_REQUEST['p'] ? $_REQUEST['p'] : 'login';

ob_start();
try//
{
    if( $p == 'login' )
    {
        if( isset($_POST['uname']) && !empty($_POST['uname'])
            && isset($_POST['pword']) && !empty($_POST['pword']) )
        {
            $login = _login($_POST['uname'], md5($_POST['pword']));
        }
        else if( isset($_COOKIE['dis-login']) && !empty($_COOKIE['dis-login']) )
        {
            $_login = $_COOKIE['dis-login'];
            setcookie('dis-login', $_login, time() + 168 * 3600);
            $param = unserialize($_login);
            $login = _login($param['ID'], $param['pwrd']);
        }
        else
            $login = false;

        if( $login )
        {
            ob_end_clean();
            header('Location: index'); exit; // 转首页
//            header('Location: home?important'); exit; // 转首页
        }
    }
    else if( $p == 'logout' )
    {
        $user_id = $_SESSION['userId'];
        if( $user_id > 0 )
        {
            $login = new DisUserLoginCtrl($user_id);
            $login->last_login();
            $login->logout();
        }

        $_SESSION['userId'] = 0;
        $_SESSION['feed']  = null;
        $_SESSION['value'] = null;
        setcookie('dis-login', '', time() -1);
    }
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();

$gSmarty->assign("title", "用户登录");
$gSmarty->assign("item", $p);
$gSmarty->assign("err", $err);
$gSmarty->display("pages/login.tpl");

/*
function _login($name, $pwrd)
{
    if( !$name || !$pwrd )
        return false;

    $login = new DisUserLoginCtrl();
    $user = $login->login($name, $pwrd);

    if( $user && $user->ID )
    {
        $_SESSION['userId'] = $user->ID;
        DisUserCtrl::set_inline($user->ID);

        if( isset($_POST['autologin']) && $_POST['autologin'] == 'on' )
        {
            setcookie('pm-login', serialize(array('ID'=>$user->ID, 'pwrd'=>$pwrd)),
                        time() + 168 * 3600);
        }
        return true;
    }
    return false;
}

ob_start();
try//
{
//    if( $_GET['v'] && $_GET['v'] == 'test' )
//    {
//        setcookie('pm-test', serialize(array('ID'=>time())), time() + 720 * 3600);
//    }

    $p = $_REQUEST['p'] ? $_REQUEST['p'] : 'login';
    $themes = DisHeadCtrl::list_themes(10);
    $gSmarty->assign("themes", $themes);

    if( $p == 'login' )
    {
        if( isset($_POST['uname']) && !empty($_POST['uname'])
            && isset($_POST['pword']) && !empty($_POST['pword']) )
        {
            $login = _login($_POST['uname'], md5($_POST['pword']));
        }
        else if( isset($_COOKIE['pm-login']) && !empty($_COOKIE['pm-login']) )
        {
            $_login = $_COOKIE['pm-login'];
            setcookie('pm-login', $_login, time() + 168 * 3600);
            $param = unserialize($_login);
            $login = _login($param['ID'], $param['pwrd']);
        }
        else
            $login = false;
echo "67<br>";
        if( $login )
        {
            ob_end_clean();
            header('Location: home?important'); exit; // 转首页
        }
    }
    else if( $p == 'logout' )
    {
        $user_id = $_SESSION['userId'];
        if( $user_id > 0 )
        {
            $login = new DisUserLoginCtrl($user_id);
            $login->last_login();
            $login->logout();
        }
//        pmCtrlUser::logout($_SESSION['userId']);

        $_SESSION['userId'] = 0;
        $_SESSION['feed']  = null;
        $_SESSION['value'] = null;
        setcookie('pm-login', '', time() -1);
    }
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();

$gSmarty->assign("title", "用户登录");
$gSmarty->assign("item", $p);
$gSmarty->assign("err", $err);

$navi = array
(
    array('购物', '团购', '网络商城', '天猫商城', '淘宝店主', '网购达人', '消费者', '购物者'),
    array('职场', '招聘', '求职', '投资人', '创业者', '工程师'),
    array('服务', '饭馆餐厅', '酒店旅店', '商场', '电影', '健身美容', '珠宝首饰', '咖啡馆'),
    array('教育', '高校教师', '中学老师', '博士生', '研究生', '大学生', '中学生', '学生社团'),
    array('生活', '父母', '老人', '青年', '中年人', '单身', '病友'),
    array('爱好', '收藏', '旅游', '户外', '游戏', ''),
    array('财经', '基金经理', '私募基金', '股民', '基民'),
//    array('技术', '计算机', '物理学', '化学', ''),
);
$gSmarty->assign("navi", $navi);

if( $p == 'desc' )
    $file = "pmail.login.desc.tpl";
else
    $file = "pmail.login.tpl";
$gSmarty->display("pages/$file");
*/
?>