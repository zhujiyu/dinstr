<?php
/**
 * DIS项目 php文件 v1.0.0
 * 个人首页
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 DIS(有向信息流)
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
require_once 'common.inc.php';
require_once 'recom.inc.php';
require_once 'view/user.inc.php';

$gSmarty = init_smarty();
ob_start();

$user = DisUserCtrl::user(10000);
$gSmarty->assign("user", $user->info());

try
{
    $chan_ids = array(9999, 10000, 20000, 30000);
    for( $i = 0; $i < 4; $i ++ )
    {
        $chan = DisChannelCtrl::chan($chan_ids[$i]);
        $chan_list[] = $chan->info();
    }
    $gSmarty->assign('chan_list', $chan_list);

    $chan = DisChannelCtrl::chan(10000);
    $member_ids = $chan->list_member_ids();
    for( $i = 0; $i < count($member_ids); $i ++ )
    {
        $u = DisUserCtrl::user($member_ids[$i]);
        $member_list[] = $u->info();
    }
    $gSmarty->assign('member_list', $member_list);

    $manager_ids = $chan->list_manager_ids();
    for( $i = 0; $i < count($manager_ids); $i ++ )
    {
        $u = DisUserCtrl::user($manager_ids[$i]);
        $manager_list[] = $u->info();
    }
    $gSmarty->assign('manager_list', $manager_list);

//    DisObject::print_array($user_list[0]);

    $info_ids = array(100010, 100011);
    for( $i = 0; $i < 2; $i ++ )
    {
        $info = DisNoteCtrl::get_note_view($info_ids[$i]);
        $head = DisHeadCtrl::head($info['head_id']);
        $info['head'] = $head->info();
        $info['head']['status'] = $head->check_status($user->ID);
        $info_list[] = $info;
    }
    $gSmarty->assign('info_list', $info_list);
//    DisObject::print_array($info_list[0]);

//    $chan = DisChannelCtrl::chan(20000);
//    $chan_list[0] = $chan->info();
//    DisObject::print_array($chan);
//    $gSmarty->assign("chan", $chan->info());
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();
$gSmarty->assign("err", $err);

$file = "pages/home.page.tpl";
$gSmarty->assign("title", "首页");
$gSmarty->display($file);

//function _login($name, $pwrd)
//{
//    if( !$name || !$pwrd )
//        return false;
//
//    $login = new DisUserLoginCtrl();
//    $user = $login->login($name, $pwrd);
//
//    if( $user && $user->ID )
//    {
//        $_SESSION['userId'] = $user->ID;
//        DisUserCtrl::set_inline($user->ID);
//
//        if( isset($_POST['autologin']) && $_POST['autologin'] == 'on' )
//        {
//            setcookie('pm-login', serialize(array('ID'=>$user->ID, 'pwrd'=>$pwrd)),
//                        time() + 168 * 3600);
//        }
//        return true;
//    }
//    return false;
//}
//
//ob_start();
//try
//{
////    if( $_GET['v'] && $_GET['v'] == 'test' )
////    {
////        setcookie('pm-test', serialize(array('ID'=>time())), time() + 720 * 3600);
////    }
//    $p = $_REQUEST['p'] ? $_REQUEST['p']: 'login';
//
//    if( $p == 'login' )
//    {
//        if( isset($_POST['uname']) && !empty($_POST['uname'])
//            && isset($_POST['pword']) && !empty($_POST['pword']) )
//        {
//            $login = _login($_POST['uname'], md5($_POST['pword']));
//        }
//        else if( isset($_COOKIE['pm-login']) && !empty($_COOKIE['pm-login']) )
//        {
//            $_login = $_COOKIE['pm-login'];
//            setcookie('pm-login', $_login, time() + 168 * 3600);
//            $_logindata = unserialize($_login);
//            $login = _login($_logindata['ID'], $_logindata['pwrd']);
//        }
//    }
//    else if( $p == 'logout' )
//    {
//        $user_id = $_SESSION['userId'];
//        if( $user_id > 0 )
//        {
//            $login = new DisUserLoginCtrl($user_id);
//            $login->last_login();
//            $login->logout();
//        }
//
//        $_SESSION['userId'] = 0;
//        $_SESSION['feed']  = null;
//        $_SESSION['value'] = null;
//        setcookie('pm-login', '', time() -1);
//
//        $themes = DisHeadCtrl::list_themes(10);
//        $gSmarty = init_smarty();
//        $gSmarty->assign("themes", $themes);
//
//        $navi = array
//        (
//            array('购物', '团购', '网络商城', '天猫商城', '淘宝店主', '网购达人', '消费者', '购物者'),
//            array('职场', '招聘', '求职', '投资人', '创业者', '工程师'),
//            array('服务', '饭馆餐厅', '酒店旅店', '商场', '电影', '健身美容', '珠宝首饰', '咖啡馆'),
//            array('教育', '高校教师', '中学老师', '博士生', '研究生', '大学生', '中学生', '学生社团'),
//            array('生活', '父母', '老人', '青年', '中年人', '单身', '病友'),
//            array('爱好', '收藏', '旅游', '户外', '游戏', ''),
//            array('财经', '基金经理', '私募基金', '股民', '基民'),
//        //    array('技术', '计算机', '物理学', '化学', ''),
//        );
//        $gSmarty->assign("navi", $navi);
//    }
//
////    $themes = pmCtrlTheme::list_themes(10);
////    $gSmarty->assign("themes", $themes);
//}
//catch (DisException $ex)
//{
//    $ex->trace_stack();
//}
//
//$err = ob_get_contents();
//ob_end_clean();
//
//if( $p == 'logout' )
//{
//    $file = "pmail.logout.tpl";
//    $gSmarty->assign("err", $err);
//    $gSmarty->assign("title", "退出天鹅镇");
//    $gSmarty->display( "pages/$file");
//}
//else if( isset($_SESSION['userId']) && $_SESSION['userId'] > 0 && DisUserCtrl::check_inline($_SESSION['userId']) )
//    header('Location: home?important'); // 转用户首页
//else
//    header('Location: guest?important'); // 转游客首页

//echo "该网站尚未开通，我们正在努力地建设中...";
//exit;
//
//$http_host = $_SERVER['HTTP_HOST'];
//if ( !preg_match('/^[1-9]\d+/', $http_host) && !preg_match('/^ud[1-9]\d+/', $http_host) )
//{
//    echo "该网站尚未开通，我们正在努力地建设中...";
//    exit;
//}
?>