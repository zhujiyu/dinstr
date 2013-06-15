<?php
/**
 * Encoding     :   UTF-8
 * Author       :   zhujiyu , zhujiyu@139.com
 * Created on   :   2011-12-29 18:05:14
 * Copyright    :   2011 社交化协同服务办公系统项目
 */
require_once 'common.inc.php';

ob_start();
try
{
    $gSmarty = init_smarty();
    $p = $_GET['p'] ? $_GET['p'] : $_POST['p'];
//    $invi = $_GET['invi'] ? $_GET['invi'] : $_POST['invi'];
    $view = $_GET['view'] ? $_GET['view'] : $_POST['view'];
    $email = $_GET['email'] ? $_GET['email'] : $_POST['email'];

    if( isset($_SESSION['userId']) && $_SESSION['userId'] > 0 && DisUserCtrl::check_inline($_SESSION['userId']) )
    {
        $user_id = $_SESSION['userId'];
        $user = DisUserCtrl::user($user_id);
        $gSmarty->assign("user", $user->info());
        DisUserCtrl::set_inline($user_id);
    }
    else
        $user_id = 0;

    if( isset($_GET['invi']) || $p == 'invite' )
    {
        $file = "pmail.using.invite.tpl";
        $title = "邀请好友";
        $view = $view ? $view : 'email';

        if( $user_id > 0 )
        {
            $invites = DisInviteCtrl::list_invite_users($user_id);
            $gSmarty->assign("invites", $invites);
//            $invites[0]['invite_user'] = pmCtrlUser::get_user_view(1000000);
//            $invites[1]['invite_user'] = pmCtrlUser::get_user_view(1000001);
//            $invites[2]['invite_user'] = pmCtrlUser::get_user_view(1000003);
        }

        if( $email && DisUserCtrl::get_uid_by_email($email) > 0 )
            throw new DisException($email.' 已经注册！');

        if( $email || $view == 'other' )
        {
            $code  = DisInviteCtrl::invite_code($user_id, $email);
            $gSmarty->assign("invite_code", $code);
        }

        if( $email )
        {
            if( !$user_id )
                throw new DisException('你尚未登录！');
            DisMailPlg::send_invite_email($email, $code, $user->attr('username'));
            $gSmarty->assign("invite_succeed", $email);
            $gSmarty->assign("email", $email);
        }

        $_ids = DisChannelCtrl::list_chan_ids();
        if( $user_id > 0 )
            $channels = $user->list_channels($_ids);
        else
            $channels = DisChannelCtrl::parse_chans($_ids);
        $gSmarty->assign("channels", $channels);
    }
    else if( isset($_GET['fb']) || $p == 'fb' )
    {
        $file = "pmail.using.feedback.tpl";
        $view = $view ? $view : 'feedback';
        $intro = $_POST['intro'] ? $_POST['intro'] : $_GET['intro'];
        $title = $view == 'apply' ? '申请注册码' : '意见反馈';

        if( $view == 'apply' && $email )
        {
            if( DisUserCtrl::get_uid_by_email($email) > 0 )
                throw new DisException($email.' 已经注册！');
            DisFeedbackCtrl::insert($view, $user_id, $intro, $email);
            $gSmarty->assign("apply_succeed", 1);
        }
        else if( $view == 'feedback' && $intro )
        {
            DisFeedbackCtrl::insert($view, $user_id, $intro, $email);
            $gSmarty->assign("feedback_succeed", 1);
        }
        else if( $view == 'list' )
        {
            $item = $_GET['item'] ? $_GET['item'] : 'feedback';
            if( $item == 'feedback' )
            {
                $datas = DisFeedbackCtrl::list_feedbacks($_GET['page']);
                for( $i = count($datas) - 1; $i >= 0; $i -- )
                {
                    $datas[$i]['user'] = DisUserCtrl::get_data($datas[$i]['user_id']);
                }
            }
            else
            {
                $datas = DisFeedbackCtrl::list_applies($_GET['page']);
            }

            $gSmarty->assign("item", $item);
            $gSmarty->assign("datas", $datas);
            $gSmarty->assign("page", $_GET['page']);
        }
    }
    else if( isset($_GET['about']) || $p == 'about' )
    {
        $file = "pmail.using.about.tpl";
        $title = "关于我们";
    }
    else if( isset($_GET['qrcode']) || $p == 'qrcode' )
    {
        ob_end_clean();
        include "include/plugin/pmQRCode.php";
//        echo "生成二维码<br><br>";
        echo '<div style="text-align: center; width: 100%; height: 100%; vertical-align: middle">';
        generateQRfromGoogle("朱继玉的二维码", 250, 'M');
        echo '</div>';
        exit();
    }
    else //if( isset($_GET['404']) || $p == '404' )
    {
        $file = "pmail.using.404.tpl";
        $title = "404-ERR";
        $gSmarty->assign("404", '你似乎走到'.DisConfigAttr::$app['name']
            .'的有效范围之外了！或者该页面我们还在加紧开发中...');
    }
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();

$gSmarty->assign("view", $view);
$gSmarty->assign("title", $title);
$gSmarty->assign("err", $err);
$gSmarty->display("pages/$file");
?>