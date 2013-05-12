<?php
/**
 * Pmail项目 PHP文件 v2.4.16
 * @package: PMAIL.FILE
 * @file   : chan.php
 * 频道页面
 * @author    : zhujiyu , zhujiyu@139.com
 * @Copyright : 2012 公众邮件网
 * @Date      : 2012-4-11
 * @encoding  : UTF-8
 * @version   : 2.4.16
 */
require_once 'common.inc.php';

$uri = $_SERVER["REQUEST_URI"];
$matches = null;
if( preg_match('/chan\/(\d+)/', $uri, $matches) )
{
    $_GET['id'] = $matches[1];
    header('LOCATION: ../chan?id='.$matches[1]);
    exit;
}

//    $a = preg_match('/chan\?id\=(\d+)/', $uri, $matches);
//if( preg_match('/chan\/(\d+)/', $uri, $matches) )
//{
//    $_GET['id'] = $matches[1];
////    header('LOCATION: ../chan?id='.$matches[1]); exit;
//}

function channel()
{
    if( $_GET['id'] )
        $channel_id = $_GET['id'];
    else if ( $_GET['n'] )
        $channel_id = DisChannelCtrl::get_id_by_name($_GET['n']);
    else if ( $_GET['d'] )
        $channel_id = DisChannelCtrl::get_id_by_domain($_GET['d']);
    return $channel_id;
}

function list_channel_role(&$members, $channel_id)
{
    $len = count($members);
    for( $i = 0; $i < $len; $i ++ )
    {
        $members[$i][member] = DisChanUserCtrl::get_data($members[$i][ID], $channel_id);
    }
    return $members;
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

    $p = $_GET['p'] ? $_GET['p']: $_POST['p'];
    $view = $_GET['view'] ? $_GET['view']: $_POST['view'];
//    $page = $_GET['page'] ? $_GET['page']: $_POST['page'];
    $channel_id = channel();

    if( $channel_id )
    {
        $channel = DisChannelCtrl::channel($channel_id);
        $chan_data = $channel->info();
        $gSmarty->assign("channel", $chan_data);

        if( $user_id > 0 )
        {
            $status = $user->get_channel_status($channel_id);
            $gSmarty->assign("status", $status);
            $role = $status[role];
        }
    }

    if( $_GET['edit'] || $p == 'edit' )
    {
        if( !$user->check_editor($channel_id) )
        {
            $p = 'disp';
            echo "你不是管理员，无法编辑该频道，已自动跳转回频道主页面";
        }
    }

    if( isset($_GET['create']) || $p == 'create' )
    {
        if( isset($_GET['name']) && !empty($_GET['name']) )
        {
            $channel = DisChannelCtrl::create_new_channel($user_id, $_GET['name'],
                    $_GET['type'], $_GET['logo'], $_GET['desc'], $_GET['tags']);
            ob_end_clean();
            header('Location: chan?id='.$channel->ID); exit;
        }

        $file = "pmail.chan.create.tpl";
        $gSmarty->assign("title", "开通新频道");
    }
    else if( $p == 'edit' )
    {
        $view = $view ? $view : 'setting';
        $file = "pmail.chan.edit.tpl";
        $gSmarty->assign("title", "开通新频道");
    }
    else if( isset($_GET['plaza']) || $p == 'plaza' )
    {
        $view = $view ? $view : 'selected';
        $file = "pmail.chan.plaza.tpl";
        $gSmarty->assign("title", "频道广场");

        $_ids = DisChannelCtrl::list_channel_ids($_GET['tag']);
        if( $user_id > 0 )
            $channels = $user->list_channels($_ids);
        else
            $channels = DisChannelCtrl::parse_channels($_ids);
        $gSmarty->assign("channels", $channels);
    }
    else if( $channel_id > 0 ) //if( $p == 'disp' || $p == 'notice' )
    {
        if( isset($_GET['subscriber']) || $view == 'subscriber' )
        {
            $view = 'subscriber';
            $gSmarty->assign("title", $chan_data['name'].'订阅者');
            $user_ids = $channel->list_subscriber_ids();
            if( $user_id > 0 )
                $subscribers = $user->list_users($user_ids);
            else
                $subscribers = DisUserCtrl::parse_users($user_ids);
            if( $role > 1 )
                list_channel_role($subscribers, $channel_id);
            $gSmarty->assign("subscribers", $subscribers);
        }
        else if( isset($_GET['member']) || $view == 'member' )
        {
            $view = 'member';
            $gSmarty->assign("title", $chan_data['name'].'成员');
            $user_ids = $channel->list_member_ids();
            if( $user_id > 0 )
                $members = $user->list_users($user_ids);
            else
                $members = DisUserCtrl::parse_users($user_ids);
            if( $role > 1 )
                list_channel_role($members, $channel_id);
            $gSmarty->assign("members", $members);
        }
        else if( (isset($_GET['applicant']) || $view == 'applicant') && $role > 1 ) //($role == 'editor' || $role == 'superuser') )
        {
            $view = 'applicant';
            $gSmarty->assign("title", $chan_data['name'].'申请列表');
            $applicants = $channel->list_applicants();
            $gSmarty->assign("applicants", $applicants);
        }
        else //if( $_GET['mail'] || $view == 'mail' )
        {
            $view = 'mail';
            $gSmarty->assign("title", $chan_data['name'].'资讯');

            if( $_GET['timeline'] || $_GET['sort'] == 'timeline' )
            {
                $sort = 'timeline';
                $period = 'timeline';
                $flow_ids = $channel->list_flow_ids(0, 20);
            }
            else
            {
                $sort = 'value';
                $flag = (int)(time() / DisConfigAttr::$intervals['quarter']);
                $period = $_GET['period'] ? $_GET['period'] : '1d';

                $flows = DisChannelCtrl::list_value_flows($channel_id, $flag, $period);
                $flow_ids = list_slice($flows[flow_ids], 0, 20);
                $gSmarty->assign("flag", $flag);
            }
            $gSmarty->assign("period", $period);

            if( $user_id > 0 )
                $mail_list = $user->list_flows($flow_ids);
            else
                $mail_list = DisStreamCtrl::list_flows($flow_ids);
            $gSmarty->assign("mail_list", $mail_list);
        }

        if( isset($_GET['notices']) && !empty($_GET['notices']) && $user_id > 0 )
        {
            $notice_ids = DisNoticeCtrl::preg_notices($_GET['notices']);
            $noti = new DisNoticeCtrl($user_id);
            if( $view == 'applicant' && $role > 1 )
            {
                $notices = $noti->parse_notice_ids($notice_ids);
                $gSmarty->assign("notices", $notices);
            }
            $noti->remove_notices($notice_ids);
        }

        if( $view == 'mail' )
        {
            if( $role > 1 )
                $file = "pmail.chan.mail.manage.tpl";
            else
                $file = "pmail.chan.mail.view.tpl";
        }
        else
        {
            if( $role > 1 )
                $file = "pmail.chan.user.manage.tpl";
            else
                $file = "pmail.chan.user.view.tpl";
        }
    }
    else
    {
        $file = "pmail.using.404.tpl";
        $gSmarty->assign("title", '无效的请求页');
        throw new DisException("无效的请求页。");
    }
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();

$gSmarty->assign("view", $view);
$gSmarty->assign("err", $err);
$gSmarty->display("pages/$file");
?>