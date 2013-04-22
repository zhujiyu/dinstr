<?php
/**
 * Pmail项目 PHP文件 v2.4.16
 * @package: PMAIL.FILE
 * @file   : ls.php
 * 列出当前的各种信息
 * @author    : zhujiyu , zhujiyu@139.com
 * @Copyright : 2012 公众邮件网
 * @Date      : 2012-4-11
 * @encoding  : UTF-8
 * @version   : 2.4.16
 */
require_once 'common.inc.php';

/**
 * zhujiyu 对该插件进行了简化，只保留
 * 加粗，斜体，下划线
 * 缩进，反缩进
 * 项目标号 项目编号
 * 加分割线 连接 图片 网络商品
 * 查看源代码 清除格式
 * */

ob_start();
try
{
    $view = $_GET['view'];
    if( !isset($_SESSION['userId']) || $_SESSION['userId'] == 0 || !DisUserCtrl::check_inline($_SESSION['userId']) )
    {
        header('Location: login'); exit; // 转登录页面
    }

    $user_id = $_SESSION['userId'];
    $user = DisUserCtrl::user($user_id);
    $gSmarty->assign("user", $user->info());
    DisUserCtrl::set_inline($user_id);

    if( $view == 'publish' || isset($_GET['publish']) )
    {
        $title = "我发出的邮件";
        $file = 'pmail.ls.mail.tpl';
        $mail_ids = $user->list_publish_mail_ids(0, 20);
        $mails = $user->list_mails($mail_ids);
        $gSmarty->assign("mail_list", $mails);
    }
    else if( isset($_GET['collect']) || $view == 'collect' )
    {
        $title = "我的收藏";
        $file = 'pmail.ls.collect.tpl';
        $collect = new DisNoteCollectCtrl($user_id);
        $mail_ids = $collect->list_mail_ids(0, 20);
        $mails = $user->list_mails($mail_ids);
        $gSmarty->assign("mail_list", $mails);
    }
    else if( $view == 'interest' || isset($_GET['interest']) )
    {
        $title = "关注的邮件列表";
        $file = 'pmail.ls.theme.tpl';
        $item = 'interest';

        $theme_ids = $user->list_interest_theme_ids(0, 20);
        $themes = $user->list_themes($theme_ids);
        $gSmarty->assign("theme_list",  $themes);
    }
    else if( $view == 'approve' || isset($_GET['approve']) )
    {
        $title = "参与的邮件列表";
        $file = 'pmail.ls.theme.tpl';
        $item = 'approve';

        $theme_ids = $user->list_approved_theme_ids(0, 20);
        $themes = $user->list_themes($theme_ids);
        $gSmarty->assign("theme_list",  $themes);
    }
    else if( isset($_GET['channel']) || $view == 'channel' )
    {
        $title = "我的频道";
        $file = 'pmail.ls.channel.tpl';
        $cu = new DisChanUserCtrl($user_id);

        if ( isset($_GET['join']) || $_GET['join'] == 'subscribe' )
        {
            $item = 'join';
            $channel_ids = $cu->list_joined_ids();
        }
        else
        {
            $item = 'subscribe';
            $channel_ids = $cu->list_subscribed_ids();
        }

        $channel_list = $user->list_channels($channel_ids);
        $gSmarty->assign("channel_list",    $channel_list);
    }
    else if( isset($_GET['friend']) || $view == 'friend' )
    {
        $title = "我的好友";
        $file = 'pmail.ls.user.tpl';

        if ( isset($_GET['follow']) || $_GET['follow'] == 'channel' )
        {
            $item = 'follow';
            $friend_ids = $user->list_follow_user_ids();
        }
        else
        {
            $item = 'fans';
            $friend_ids = $user->list_fan_user_ids();
        }

        $friend_list = $user->list_users($friend_ids);
        $gSmarty->assign("friend_list", $friend_list);
    }
    else
    {
        header('Location: home?feed');
        exit;
    }
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$error = ob_get_contents();
ob_end_clean();

$gSmarty->assign("item", $item);
$gSmarty->assign("err", $error);
$gSmarty->assign("title", $title);

$file = $file ? $file : 'pmail.using.404.tpl';
$gSmarty->display("pages/$file");
?>