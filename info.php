<?php
/**
 * DINSTR项目 PHP文件 v1.0.0
 * @package: DIS.PAGE
 * @file   : info.php
 * 信息详细
 *
 * @author   : 朱继玉<zhuhz82@126.com>
 * @Copyright: 2013 有向信息流
 * @Date     : 2013-04-16
 * @encoding : UTF-8
 * @version  : 1.0.0
 */
require_once 'common.inc.php';

$uri = $_SERVER['REQUEST_URI'];
$matches = null;
preg_match('/info\?([0-9]*)$/i', $uri, $matches);
$head_id = $matches[1];

$gSmarty = init_smarty();
ob_start();

try
{
    $user = DisUserCtrl::user(10000);
    $gSmarty->assign("user", $user->info());

    $head = DisHeadCtrl::head($head_id);
    $gSmarty->assign("title", $head->attr('content'));
    
    $note_id = $head->attr('note_id');
    $info = DisNoteCtrl::get_note_view($note_id);
    $info['head'] = $head->info();
    $gSmarty->assign("info", $info);
    
    $notes = DisNoteCtrl::list_user_infos($user->ID);
//    DisObject::print_array($notes);
//    $user->list_infos($mail_ids);
//    $gSmarty->assign("info", DisNoteCtrl::get_note_view($note_id));
    
//    $gSmarty->assign("head", $head->info());
//    DisObject::print_array($head);
//    $head_view = $head->head_view();
//    DisObject::print_array($head_view);
//    DisObject::print_array($info);
//    $info_view = DisNoteCtrl::get_note_view($note_id);
//    DisObject::print_array($info_view);
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();

$gSmarty->assign("err", $err);
$gSmarty->display("page/info.tpl");
/*
function merge($notices)
{
    $mails = $replies = $approves = array();
    $len = count($notices);

    for( $i = 0; $i < $len; $i ++ )
    {
        if( $notices[$i][type] == 'mail' )
            $mails[] = $notices[$i];
        else if( $notices[$i][type] == 'reply' )
            $replies[] = $notices[$i];
        else if( $notices[$i][type] == 'approve' )
            $approves[] = $notices[$i];
    }

    return array('mails'=>$mails, 'replies'=>$replies, 'approves'=>$approves);
}

ob_start();
try
{
    $gSmarty = init_smarty();
    if( isset($_SESSION['userId']) && $_SESSION['userId'] > 0 && DisUserCtrl::check_inline($_SESSION['userId']) )
    {
        $user_id = $_SESSION['userId'];
        $user = DisUserCtrl::user($user_id);
        $gSmarty->assign("user", $user->info());
        DisUserCtrl::set_inline($user_id);
    }
    else
        $user_id = 0;

    if( $_GET['id'] )
    {
//        $mail = pmCtrlMail::get_data($_GET['id']);
        $mail = DisNoteCtrl::get_note_view($_GET['id']);
        $mail_id = $mail[ID];
    }

    if( $mail_id > 0 )
    {
        $view = $_GET['view'] ? $_GET['view'] : 'mail';
        $gSmarty->assign("view", $view);

        $theme_id = $mail['theme_id'];
        $theme = DisHeadCtrl::head($theme_id);
        $theme_data = $theme->head_view();

        if( $user_id > 0 )
        {
            $theme_data['status'] = $theme->check_status($user_id);
            if( $theme_data[channel] && $user_id > 0 )
                $theme_data[channel][member] = $user->get_channel_status($theme_data[channel][ID]);
        }
        $gSmarty->assign("mail", $mail);
        $gSmarty->assign("theme", $theme_data);

        if( $_GET['notices'] )
        {
            $noti = new DisNoticeCtrl($user_id);
            $notice_ids = DisNoticeCtrl::preg_notices($_GET['notices']);

            $notilst = $noti->parse_notice_ids($notice_ids);
            $notices = merge($notilst);
            $gSmarty->assign("notices", $notices);

            if( $user_id > 0 )
                $noti->remove_notices($notice_ids);
        }

        if( isset($_GET['interest']) || $view == 'interest' || isset($_GET['approval']) || $view == 'approval' )
        {
            $file = "pmail.mail.user.tpl";
            if( $_GET['interest'] || $view == 'interest' )
                $user_ids  = $theme->list_interest_user_ids();
            else //if( $_GET['approval'] || $view == 'approval' )
                $user_ids  = $theme->list_approved_user_ids();

            if( $user_id > 0 )
                $user_list = $user->list_users($user_ids);
            else
                $user_list = DisUserCtrl::parse_users($user_ids);
            $gSmarty->assign("user_list", $user_list);
        }
        else
        {
            $file = "pmail.mail.view.tpl";
            $mail = DisNoteCtrl::note ($mail_id);
            $mail_ids = $mail->list_child_ids();

            if( $user_id > 0 )
                $mail_list = $user->list_mails($mail_ids);
            else
                $mail_list = DisNoteCtrl::parse_mails($mail_ids);
            $gSmarty->assign("mail_list", $mail_list);
        }
    }
    else if( $user_id > 0 )
    {
        $file = "pmail.mail.edit.tpl";
        $ml = new DisMoneyLogCtrl($user_id);
        $logs = $ml->list_logs();
        $gSmarty->assign("money_list", $logs);
        $charge = $ml->hasCharge();
        $gSmarty->assign("charged", $charge);
    }
    else
    {
        ob_end_clean();
        header('Location: guest?feed'); exit; // 转游客页
    }
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();

$gSmarty->assign("title", '写新邮件');
$gSmarty->assign("err", $err);
$gSmarty->display("pages/$file");
*/
?>