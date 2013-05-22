<?php
/**
 * PMAIL项目 php文件 v1.9.25
 *
 * 个人页面
 * @Encoding  :   UTF-8
 * @Author    :   zhujiyu , zhujiyu@139.com
 * @Date      :   2011-10-5 3:32:53
 * @Copyright :   2011 社交化协同服务办公系统项目
 */
require_once 'common.inc.php';

function target()
{
    if( isset($_GET['id']) )
        $user_id = $_GET['id'];
    else if( isset($_GET['u']) )
        $user_id = DisUserCtrl::get_uid_by_name($_GET['u']);
    else if( isset($_GET['d']) )
        $user_id = DisUserCtrl::get_uid_by_domain($_GET['d']);
    else if( isset($_GET['e']) )
        $user_id = DisUserCtrl::get_uid_by_email($_GET['e']);
    else if( isset($_SESSION['userId']) )
        $user_id = $_SESSION['userId'];
    else
        $user_id = 0;
    return $user_id;
}

ob_start();
try
{
    $p = $_GET['p'] ? $_GET['p'] : $_POST['p'];
    $view = $_GET['view'] ? $_GET['view'] : $_POST['view'];
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

    if( isset($_GET['edit']) || $_GET[p] == 'edit' )
    {
        $file = "pmail.user.setting.tpl";
        if( !$user_id )
            throw new DisException('你尚未登录！');
    }
    else
    {
        $target_id = target();
        if( $target_id == 0 )
            throw new DisException('缺少用户对象');

        $target_user = DisUserCtrl::user($target_id);
        $gSmarty->assign("target_user", $target_user->info());
        $gSmarty->assign("title", $target_user->attr('username'));

        if( $target_id != $user_id && $user_id > 0 )
        {
            $relation[following] = $user->check_follow($target_id);
            $relation[followed]  = $target_user->check_follow($user_id);
            $gSmarty->assign("relation", $relation);
        }

        if( isset($_GET['notices']) && !empty($_GET['notices']) && $user_id > 0 )
        {
            $notice_ids = DisNoticeCtrl::preg_notices($_GET['notices']);
            $noti = new DisNoticeCtrl($user_id);
            $noti->remove_notices($notice_ids);
        }

        if( isset($_GET['join']) || $view == 'join' )
        {
            $file = "pmail.user.channel.tpl";
            $cu = new DisChanUserCtrl($target_id);
            $join_ids = $cu->list_joined_ids();
            $join_channels = $target_user->list_channels($join_ids);
            $gSmarty->assign("join_channels", $join_channels);
        }
        else if( isset($_GET['fans']) || $view == 'fans' )
        {
            $file = "pmail.user.friend.tpl";
            if( isset($_GET['notice']) || $p == 'notice' )
            {
                $notices = DisNoticeCtrl::remove_fans_notices($user_id);
                $gSmarty->assign("notices", $notices);
            }

            $fan_ids = $target_user->list_fan_user_ids();
            $fan_users = $target_user->list_users($fan_ids);
            $gSmarty->assign("fan_users", $fan_users);
        }
        else //if( isset($_GET['mail']) || $view == 'mail' )
        {
            $file = "pmail.user.mail.tpl";
            $mail_ids = $target_user->list_publish_note_ids(0, 20);
            $pub_mails = $target_user->list_mails($mail_ids);
            $gSmarty->assign("pub_mails", $pub_mails);
        }
    }
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();

$gSmarty->assign("err", $err);
$gSmarty->display("pages/$file");
?>