<?php
/**
 * Pmail项目 PHP文件 v2.4.16
 * @package: PMAIL.FILE
 * @file   : home.php
 * 个人首页
 * @author    : zhujiyu , zhujiyu@139.com
 * @Copyright : 2012 公众邮件网
 * @Date      : 2012-4-11
 * @encoding  : UTF-8
 * @version   : 2.4.16
 */
require_once 'common.inc.php';

ob_start();
try
{
    $view = $_GET['view'] ? $_GET['view'] : $_POST['view'];
    if( isset($_SESSION['userId']) && $_SESSION['userId'] > 0 && DisUserCtrl::check_inline($_SESSION['userId']) )
    {
        ob_end_clean();
        header('Location: home?important'); exit; // 转用户页面
    }

    if( !isset($_COOKIE['guest-subscribes']) )
        setcookie('guest-subscribes', json_encode(DisConfigAttr::$guest_chans), time() + 3600*24);
    setcookie('guest-subscribes', json_encode(DisConfigAttr::$guest_chans), time() + 3600*24);
    $cuCookie = json_decode( preg_replace('/\\\"/', '"', $_COOKIE['guest-subscribes']) );
    $chanids = $cuCookie->ids;

    if( isset($_GET['important']) || $view == 'important' )
    {
        $title = "重要邮件";
        $file = "pmail.guest.important.tpl";
        $period = $_GET['period'] ? $_GET['period'] : '1d';
        $gSmarty->assign("period", $period);

        $weights = array();
        $len = count($chanids);
        for( $i = 0; $i < $len; $i ++ )
        {
            $weights[$chanids[$i]] = array('weight'=>$cuCookie->weights[$i], 'rank'=>$cuCookie->ranks[$i]);
        }

        $value = DisValueCtrl::read_ctrler($period);
        $value->init($chanids, $weights);
        $flow_ids = $value->read_value_ids(0, 20);
        DisValueCtrl::save_ctrler($value);
    }
    else
    {
        $title = "最新邮件";
        $file = "pmail.guest.feed.tpl";
//        $_SESSION['guest-feed'] = null;

        $feed = DisGuestFeedCtrl::read_ctrler($chanids);
        $feed->read_feed();
        $flow_ids = $feed->list_flow_ids($chanids, 0, 20);
        DisGuestFeedCtrl::save_ctrler($feed);
    }

    $flow_list = DisNoteFlowCtrl::list_flows($flow_ids);
    $gSmarty->assign("mail_list", $flow_list);

    $_ids = DisChannelCtrl::list_channel_ids();
    $channels = DisChannelCtrl::parse_channels($_ids);
    $gSmarty->assign("channels", $channels);

    $subscribers = DisChannelCtrl::parse_channels($chanids);
    $gSmarty->assign("subscribers", $subscribers);
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
