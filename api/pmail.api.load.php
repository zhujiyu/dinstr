<?php
/**
 * @package: PMAIL.INTE.API
 * @file   : pmail.api.mail.php
 * 邮件API
 * @author    : zhujiyu , zhujiyu@139.com
 * @Copyright : 2012 公众邮件网
 * @Date      : 2012-4-11
 * @encoding  : UTF-8
 * @version   : 2.4.11
 */
require_once '../common.inc.php';

function display_mails($mails, $gSmarty)
{
    $len = count( $mails );
    for( $i = 0; $i < $len; $i ++ )
    {
        $gSmarty->assign('mail', $mails[$i]);
        $gSmarty->display('mail/pmail.mail.theme.tpl', $mails[$i]['ID']);
    }
}

function display_collects($mails, $gSmarty)
{
    $len = count( $mails );
    for( $i = 0; $i < $len; $i ++ )
    {
        $gSmarty->assign('mail', $mails[$i]);
        $gSmarty->display('mail/pmail.collect.tpl', $mails[$i]['ID']);
    }
}

function display_flows($flows, $gSmarty)
{
    $len = count( $flows );
    for( $i = 0; $i < $len; $i ++ )
    {
        $gSmarty->assign('mail', $flows[$i]);
        $gSmarty->display('mail/pmail.flow.tpl', $flows[$i]['ID']);
    }
}

function display_themes($themes, $gSmarty)
{
    $len = count ($themes);
    for( $i = 0; $i < $len; $i ++ )
    {
        $gSmarty->assign('theme', $themes[$i]);
        $gSmarty->display('mail/pmail.theme.detail.tpl', $themes[$i]['ID']);
    }
}

function load_notice($item, $user, $page)
{
    $notice = new DisNoticeCtrl($user->ID);
    if( isset($_GET['all']) || $item == 'all' )
    {
        $notices = $notice->list_all_notices($page);
    }
    else if( isset($_GET['mail']) || $item == 'mail' )
    {
    }

    $notice_list = array();
    $len = count($notices);
    for( $i = 0; $i < $len; $i ++ )
    {
        try
        {
            $_notice = $notice->_parse($notices[$i]);
        }
        catch( DisException $ex )
        {
//            $ex->trace_stack();
            continue;
        }
        $notice_list[] = $_notice;
    }

    return $notice_list;
}

function preg_ext()
{
    $_name = $_GET['extname'] ? $_GET['extname'] : $_POST['extname'];
    $_value = $_GET['extvalue'] ? $_GET['extvalue'] : $_POST['extvalue'];

    $matches = null;
    if( preg_match_all("/\w+/", $_name, $matches) )
        $keys = $matches[0];
    if( preg_match_all("/\w+/", $_value, $matches) )
        $values = $matches[0];

    return array('keys'=>$keys, 'values'=>$values);
}

function ext_param($param, $ext)
{
    if( in_array($param, $ext['keys']) )
    {
        $index = array_search($param, $ext['keys']);
        return $ext[values][$index];
    }
    return '';
}

function important_flows($page, $count)
{
    $period = ext_param('period', preg_ext());
    if( !$period )
        throw new DisException('参数不合法，没有设置时间段');

    $value = DisValueCtrl::read_ctrler($period);
    $flow_ids = $value->read_value_ids($page, $count);
    DisValueCtrl::save_ctrler($value);
    return $flow_ids;
}

function _value_flows($channel_id, $ext, $page, $count)
{
    $period = ext_param('period', $ext);
    if( !$period )
        throw new DisException('参数不合法，没有设置时间段');
    $flag = ext_param('flag', $ext);
    if( !$flag )
        $flag = (int)(time() / DisConfigAttr::$intervals['quarter']);
    $flows = DisChannelCtrl::list_value_flows($channel_id, $flag, $period);

    if( $page < 10 )
        $flow_ids = list_slice($flows[flow_ids], $page * $count, $count);
    else
        $flow_ids = array();
    return $flow_ids;
}

function _time_flows($channel_id, $page, $count)
{
    $channel = DisChannelCtrl::channel($channel_id);
    return $channel->list_flow_ids($page, $count);
}

function channel_flows($page, $count)
{
    $ext = preg_ext();
    $channel_id = ext_param('channel_id', $ext);
    if( !$channel_id )
        throw new DisException('参数不合法，没有操作对象');

    $sort = ext_param('sort', $ext);
    if( $sort == 'value' )
        return _value_flows($channel_id, $ext, $page, $count);
    else
        return _time_flows($channel_id, $page, $count);
}

function user_mails($page, $count)
{
    $ext = preg_ext();
    $target_id = ext_param('target_id', $ext);
    if( !$target_id )
        throw new DisException('参数不合法，没有操作对象');
    $target_user = DisUserCtrl::user($target_id);
    $mail_ids = $target_user->list_publish_note_ids($page, $count);
    return $mail_ids;
}

try
{
    $p = $_GET['p'] ? $_GET['p'] : 'parse';
    $item = $_GET['item'] ? $_GET['item'] : 'flow';

    if( !isset($_SESSION['userId']) || $_SESSION['userId'] == 0 || !DisUserCtrl::check_inline($_SESSION['userId']) )
    {
        if( $p == 'more' )
            $p = 'guest-more';
        else if( $p == 'parse' )
            $p = 'guest-parse';

        if( $p != 'guest-more' && $p != 'guest-parse' )
            throw new DisException("没有登录！");
        $user_id = 0;
    }
    else
    {
        $user_id = $_SESSION['userId'];
        $user = DisUserCtrl::user($user_id);
    }

    if( $p == 'more' )
    {
        $page = $_GET['page'] ? $_GET['page'] : 0;
        $count = $_GET['count'] ? $_GET['count'] : 20;

        if( isset($_GET['important']) || $item == 'important' )
        {
            $flow_ids = important_flows($page, $count);
            $flows = $user->list_flows($flow_ids);
            display_flows($flows, $gSmarty);
        }
        else if( isset($_GET['reply']) || $item == 'reply' )
        {
            $mail_ids = $user->list_reply_mail_ids($page, $count);
            $mails = $user->list_mails($mail_ids);
            display_mails($mails, $gSmarty);
        }
        else if( isset($_GET['collect']) || $item == 'collect' )
        {
            $collect = new DisNoteCollectCtrl($user->ID);
            $mail_ids = $collect->list_note_ids($page, $count);
            $mails = $user->list_mails($mail_ids);
            display_collects($mails, $gSmarty);
        }
        else if( isset($_GET['publish']) || $item == 'publish' )
        {
            $mail_ids = $user->list_publish_note_ids($page, $count);
            $mails = $user->list_mails($mail_ids);
            display_mails($mails, $gSmarty);
        }
        else if( isset($_GET['interest']) || $item == 'interest' )
        {
            $theme_ids = $user->list_interest_theme_ids($page, $count);
            $themes = $user->list_themes($theme_ids);
            display_themes($themes, $gSmarty);
        }
        else if( isset($_GET['approve']) || $item == 'approve' )
        {
            $theme_ids = $user->list_approved_head_ids($page, $count);
            $themes = $user->list_themes($theme_ids);
            display_themes($themes, $gSmarty);
        }
        else if( isset($_GET['user-mail']) || $item == 'user-mail' )
        {
            $mail_ids = user_mails($page, $count);
            $pub_mails = $user->list_mails($mail_ids);
            display_mails($pub_mails, $gSmarty);
        }
        else if( isset($_GET['channel-mail']) || $item == 'channel-mail' )
        {
            $flow_ids = channel_flows($page, $count);
            $flow_list = $user->list_flows($flow_ids);
            display_flows($flow_list, $gSmarty);
        }
    }
    else if( $p == 'guest-more' )
    {
        $page = $_GET['page'] ? $_GET['page'] : 0;
        $count = $_GET['count'] ? $_GET['count'] : 20;

        if( isset($_GET['important']) || $item == 'important' )
        {
            $flow_ids = important_flows($page, $count);
            $flow_list = DisStreamCtrl::list_flows($flow_ids);
            display_flows($flows, $gSmarty);
        }
        else if( isset($_GET['channel-mail']) || $item == 'channel-mail' )
        {
            $flow_ids = channel_flows($page, $count);
            $flow_list = DisStreamCtrl::list_flows($flow_ids);
            display_flows($flow_list, $gSmarty);
        }
        else if( isset($_GET['user-mail']) || $item == 'user-mail' )
        {
            $mail_ids = user_mails($page, $count);
            $mail_list = DisNoteCtrl::parse_mails($mail_ids);
            display_mails($mail_list, $gSmarty);
        }
    }
    else if( $p == 'parse' )
    {
        if( isset($_GET['flow']) || $item == 'flow' )
        {
            $flows = $user->list_flows($_GET['flow_ids']);
            display_flows($flows, $gSmarty);
        }
        else if( isset($_GET['mail']) || $item == 'mail' )
        {
            $mails = $user->list_mails($_GET['mail_ids']);
            display_mails($mails, $gSmarty);
        }
        else if( isset($_GET['theme']) || $item == 'theme' )
        {
            $themes = $user->list_themes($_GET['theme_ids']);
            display_themes($themes, $gSmarty);
        }
    }
    else if( $p == 'guest-parse' )
    {
        if( isset($_GET['flow']) || $item == 'flow' )
        {
            $flow_list = DisStreamCtrl::list_flows($_GET['flow_ids']);
            display_flows($flow_list, $gSmarty);
        }
        else if( isset($_GET['mail']) || $item == 'mail' )
        {
            $mail_list = DisNoteCtrl::parse_mails($_GET['mail_ids']);
            display_mails($mail_list, $gSmarty);
        }
        else if( isset($_GET['theme']) || $item == 'theme' )
        {
            $themes = DisHeadCtrl::parse_heads($_GET['theme_ids']);
            display_themes($themes, $gSmarty);
        }
    }
    else if( $p == 'load-notice' )
    {
        $notice_list = load_notice($item, $user, $_GET['page']);
        $gSmarty->assign("user", $user->info());
        $gSmarty->assign("notices",  $notice_list);
        $gSmarty->display("chan/pmail.notice.tpl");
    }
}
catch (DisException $ex)
{
    echo "<div class=\"pm-err\">";
    $ex->trace_stack();
    echo "</div>";
}
?>