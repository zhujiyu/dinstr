<?php
/**
 * DIS项目 PHP文件 v1.0.0
 * @package: DIS.PAGE
 * @file   : home.php
 * 个人首页
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 DIS(有向信息流)
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
require_once 'common.inc.php';

ob_start();
try
{
    $view = $_GET['view'] ? $_GET['view'] : $_POST['view'];
    if( !isset($_SESSION['userId']) || $_SESSION['userId'] == 0 || !DisUserCtrl::check_inline($_SESSION['userId']) )
    {
        ob_end_clean();
        header('Location: guest?important');
        exit; // 转游客页
    }

    $user_id = $_SESSION['userId'];
    $user = DisUserCtrl::user($user_id);
    $gSmarty->assign("user", $user->info());
    DisUserCtrl::set_inline($user_id);

    if( isset($_GET['important']) || $view == 'important' )
    {
        $title = "重要邮件";
        $file = "pmail.home.important.tpl";
        $period = $_GET['period'] ? $_GET['period'] : '1d';
        $gSmarty->assign("period", $period);

        $cu = new DisChanUserCtrl($user_id);
        $chanids = $cu->list_subscribed_ids();
        $weights = $cu->list_subscribed_weights();

        $value = DisValueCtrl::read_ctrler($period);
        $value->init($chanids, $weights);
        $flow_ids = $value->read_value_ids(0, 20);
        DisValueCtrl::save_ctrler($value);

        $flow_list = $user->list_flows($flow_ids);
        $gSmarty->assign("mail_list", $flow_list);
    }
    else if( $view == 'reply' || isset($_GET['reply']) )
    {
        $title = "回复我的邮件";
        $file = 'pmail.home.reply.tpl';

        $mail_ids = $user->list_reply_mail_ids(0, 20);
        $mail_list = $user->list_infos($mail_ids);
        $gSmarty->assign("mail_list", $mail_list);
    }
    else //if ( isset($_GET['feed']) || $view == 'feed' )
    {
        $title = "最新邮件";
        $file = "pmail.home.feed.tpl";
//        $_SESSION['feed'] = null;

        $feed = DisFeedCtrl::read_ctrler($user_id);
        $feed->read_feed();
        $flow_ids = $feed->list_flow_ids(0, 20);
        DisFeedCtrl::save_ctrler($feed);

        $flow_list = $user->list_flows($flow_ids);
        $gSmarty->assign("mail_list", $flow_list);
//        pmObject::print_array($flow_list[0][objects]);
    }

    $mn = new DisMoneyLogCtrl($user_id);
    $charge = $mn->hasCharge();
    $gSmarty->assign("charged", $charge);
    $logs = $mn->list_logs();
    $gSmarty->assign("money_list", $logs);
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();
$file = $file ? $file : "pmail.using.404.tpl";
$gSmarty->assign("err", $err);
$gSmarty->assign("title", "$title");
$gSmarty->display( "pages/$file");
?>