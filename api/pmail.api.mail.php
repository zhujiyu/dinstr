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

    function display_mails($mail_ids, $smarty)
    {
        $mails = DisNoteCtrl::parse_mails($mail_ids);
        $count = count($mails);
        for( $i = 0; $i < $count; $i ++ )
        {
//            $mail = pmCtrlMail::get_mail_view($mail_ids[$i]);
            $smarty->assign('mail', $mails[$i]);
            $smarty->display('mail/pmail.mail.tpl', $mails[$i][ID]);
        }
    }

    function display_flows($flows, $smarty)
    {
        $mail_ids = array();
        $count = count($flows);
        for( $i = 0; $i < $count; $i ++ )
        {
            if( in_array($flows[$i][ID], $mail_ids) )
                continue;
            array_push($mail_ids, $flows[$i][ID]);

            $smarty->assign('mail', $flows[$i]);
            $smarty->display('mail/pmail.flow.tpl', $flows['ID']);
        }
    }

    function filter_weight($user_id)
    {
        if( !isset($_REQUEST['weight']) || (int)$_REQUEST['weight'] == 0 )
            return true;

        $param = DisUserParamCtrl::get_data($user_id);
        if( ((int)$param['imoney'] < 0 && (int)$_REQUEST['weight'] >= 100)
               || (int)$param['imoney'] - (int)$_REQUEST['weight'] < -1000 )
        {
            echo '<div class="pm-err">';
            echo '<h3>你的金币余额不足，请重新设置资讯优先级。</h3>';
            echo '<div class="pm-content-border"></div>';
            echo '<div class="pm-desc">注：天鹅镇金币使用规则：<ul>';
            echo '<li>1. 每个账户每天登录后，可以领取100金币</li>';
            echo '<li>2. 当账户金币余额小于0时，将不能发送需要优先级达到和超过100金币（含）的资讯</li>';
            echo '<li>3. 资讯发送后，金币余额不能小于-1000</li></ul>';
            echo '</div></div>';
            return false;
        }
        return true;
    }

try
{
    $p = $_GET['p'] ? $_GET['p']: $_POST['p'];
    $item = $_GET['item'] ? $_GET['item']: $_POST['item'];
    $mail_id = (int)($_POST['mail_id'] ? $_POST['mail_id'] : $_GET['mail_id']);

    if( !isset($_SESSION['userId']) || $_SESSION['userId'] == 0 || !DisUserCtrl::check_inline($_SESSION['userId']) )
    {
        if( $p != 'load' )
            throw new DisException("没有登录！");
        $user_id = 0;
    }
    else
    {
        $user_id = $_SESSION['userId'];
    }

    if( $mail_id )
        $mail = DisNoteCtrl::mail($mail_id);
    $mail_ids = $flow_ids = array();

    if( $p == 'publish' )
    {
        if( !filter_weight($user_id) )
            return;
        $channel_id = (int)$_REQUEST['channel_id'];

        $mail = DisNoteCtrl::new_mail($user_id, $_REQUEST['title'], $_REQUEST['content'], $channel_id,
                $_REQUEST['photos'], $_REQUEST['goods']);
        $flow_id = $mail->send($user_id, $channel_id, (int)$_REQUEST['weight']);

        $user = DisUserCtrl::user($user_id);
        $flows = $user->list_flows(array($flow_id));
        display_flows($flows, $gSmarty);
    }
    else if( $p == 'reply' )
    {
        if( !filter_weight($user_id) )
            return;

        if( $_REQUEST['theme_id'] )
        {
            $theme = DisHeadCtrl::get_data((int)$_REQUEST['theme_id']);
            $parent_id = (int)$theme['mail_id'];
        }
        else if( $_REQUEST['parent'] )
            $parent_id = (int)$_REQUEST['parent'];

        $parent = DisNoteCtrl::mail($parent_id);
        $reply = $parent->reply($user_id, $_REQUEST['content'], $_REQUEST['photos'], $_REQUEST['goods']);

        $gSmarty->assign('user', DisUserCtrl::get_data($user_id));
        if( isset($_REQUEST['channel_id']) && (int)$_REQUEST['channel_id'] > 0 )
        {
            $flow_id = $reply->send($user_id, (int)$_REQUEST['channel_id'], (int)$_REQUEST['weight']);
            $user = DisUserCtrl::user($user_id);
            $flows = $user->list_flows(array($flow_id));
            display_flows($flows, $gSmarty);
        }
        else
        {
            display_mails(array($reply->ID), $gSmarty);
        }
    }
    else if( $p == 'load' )
    {
        if( $user_id > 0 )
            $gSmarty->assign('user', DisUserCtrl::get_data($user_id));
        else
            $gSmarty->assign('user', array('ID'=>0));

        if( $item == 'whole' )
        {
            echo "<span  class='content'>".$mail->attr('content')."</span>";
        }
        else if( $item == 'parent' )
        {
            $mail_ids = $mail->list_parent_ids();
            display_mails  ($mail_ids, $gSmarty);
        }
        else if( $item == 'children' )
        {
            $mail_ids = $mail->list_child_ids();
            display_mails ($mail_ids, $gSmarty);
        }
    }
    else if( $p == 'manage' )
    {
        if( $item == 'delete' )
        {
            $flow = new DisNoteFlowCtrl((int)$_REQUEST['flow_id']);
            $flow->delete($user_id);
            echo '<div class="pm-success">删除成功！</div>';
        }
        else if( $item == 'collect' )
        {
            $collect = new DisNoteCollectCtrl($user_id);
            $collect->insert($mail_id);
            echo '<div class="pm-success">收藏成功！</div>';
        }
        else if( $item == 'cancel-collect' )
        {
            $collect = new DisNoteCollectCtrl($user_id);
            $collect->delete($mail_id);
            echo '<div class="pm-success">取消收藏！</div>';
        }
    }
}
catch (DisException $ex)
{
    echo "<div class=\"pm-err\">";
    $ex->trace_stack();
    echo "</div>";
}
?>