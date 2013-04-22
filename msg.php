<?php
/**
 * Pmail项目 PHP文件 v2.4.16
 * @package: PMAIL.FILE
 * @file   : msg.php
 * 私信
 * @author    : zhujiyu , zhujiyu@139.com
 * @Copyright : 2012 公众邮件网
 * @Date      : 2012-4-11
 * @encoding  : UTF-8
 * @version   : 2.4.16
 */
require_once 'common.inc.php';

$ur = $_GET['ur'] ? $_GET['ur'] : $_POST['ur'];
foreach( $_GET as $key => $value )
{
    if( $key == 'ur' )
        $ur = $value;
    else if( !$ur && !$value )
        $ur = $key;
}

ob_start();
try
{
    if( !isset($_SESSION['userId']) || $_SESSION['userId'] == 0 || !DisUserCtrl::check_inline($_SESSION['userId']) )
    {
        header('Location: login'); exit;
    }
//    if ( !pmApiUser::check_inline() )
//    {
//        header('Location: login');// 转登录页面
//        exit ;
//    }

    $user_id = $_SESSION['userId'];
    $user = DisUserCtrl::user($user_id);
    $gSmarty->assign("user", $user->info());
    DisUserCtrl::set_inline($user_id);

    if( $ur )
    {
        $file = "pmail.message.list.tpl";
        $friend_id = DisUserCtrl::get_user_id($ur);
        $friend = DisUserCtrl::get_data($friend_id);
        $gSmarty->assign("friend", $friend);

        $list = DisMessageCtrl::list_user_message($user_id, $friend_id);
        $gSmarty->assign("user_messages", $list);
    }
    else
    {
        $file = "pmail.message.users.tpl";
        $list = DisMessageCtrl::list_messages($user_id);
        $gSmarty->assign("user_list", $list);
    }
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();

$gSmarty->assign("title", "私信");
$gSmarty->assign("err", $err);
$gSmarty->display("pages/$file");
?>