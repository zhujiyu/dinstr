<?php
/**
 * @package: DINSTR.API
 * @file   : pmail.api.channel.php
 * 频道API
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 DINSTR(有向信息流)
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
require_once '../common.inc.php';

ob_start();
try
{
    $p = $_GET['p'] ? $_GET['p']: $_POST['p'];
    $_SESSION['userId'] = 1000000;
    DisUserCtrl::set_inline(1000000);

    if( !isset($_SESSION['userId']) || $_SESSION['userId'] == 0
            || !DisUserCtrl::check_inline($_SESSION['userId']) )
    {
        if( !isset($_GET['disp']) && $p != 'disp' )
            throw new DisException("没有登录！");
        $user_id = 0;
    }
    else
    {
        $user_id = (int)$_SESSION['userId'];
        $user = DisUserCtrl::user($user_id);
    }
    $channel_id = (int)$_GET['id'];

    if( isset($_GET['disp']) || $p == 'disp' )
    {
        $chan_info = DisChannelCtrl::get_data($channel_id);
        if( $user_id > 0 )
        {
            $user = DisUserCtrl::user($user_id);
            $chan_info['status'] = $user->get_channel_status($channel_id);
        }
        $val['channel'] = $chan_info;
    }
    else if( isset($_GET['user']) || $p == 'user' )
    {
        if( !$channel_id )
            throw new DisException("请输入操作频道的ID！");
        $channel = DisChannelCtrl::channel($channel_id);

        switch($_GET['item'])
        {
            case 'subscribe':
                $channel->add_subscriber($user_id);
                break;
            case 'cancel-subscribe':
                $channel->remove_subscriber($user_id);
                break;
            case 'join-apply':
                $channel->apply($user_id, $_GET['reason']);
                break;
            case 'quit':
                $channel->remove_member($user_id);
                break;
            default:
                throw new DisParamException('没有这种操作');
                break;
        }

        $val['succeed'] = 1;
        $val['channel'] = DisChannelCtrl::get_data($channel_id);
    }
    else if( isset($_GET['manage']) || $p == 'manage' )
    {
        if( !$channel_id )
            throw new DisException("请输入操作频道的ID！");
        $member_id = (int)$_GET['user_id'];

        switch($_GET['item'])
        {
            case 'accept-apply':
                $channel = new DisChannelCtrl($channel_id);
                $channel->accept_apply((int)$_GET['applicant_id'], $member_id);
                break;
            case 'refuse-apply':
                $channel = new DisChannelCtrl($channel_id);
                $channel->refuse_apply((int)$_GET['applicant_id'], $member_id);
                break;
            case 'editor-role':
                $cu = new DisChanUserCtrl($member_id);
                $cu->manage_edit_role('editor', $channel_id);
                break;
            case 'member-role':
                $cu = new DisChanUserCtrl($member_id);
                $cu->manage_edit_role('member', $channel_id);
                break;
            default:
                throw new DisParamException('没有这种操作');
                break;
        }

        $val['succeed'] = 1;
        $val['channel'] = DisChannelCtrl::get_data($channel_id);
    }
    else if( isset($_GET['weight']) || $p == 'weight' )
    {
        if( !$channel_id )
            throw new DisException("请输入操作频道的ID！");
        $cu = new DisChanUserCtrl($user_id, $channel_id);

        switch($_GET['item'])
        {
            case 'rank':
                $cu->reset_weight((int)$_GET['weight'], (int)$_GET['rank']);
                break;
            case 'plus':
                $cu->plus_weight();
                break;
            case 'minus':
                $cu->minus_weight();
                break;
            default:
                throw new DisParamException('没有这种操作');
                break;
        }

        $val['succeed'] = 1;
        $val['channel'] = DisChannelCtrl::get_data($channel_id);
        $val['subscribe'] = DisChanUserCtrl::get_data($user_id, $channel_id);
    }
    else if( isset($_GET['edit']) || $p == 'edit' )
    {
        if( !$channel_id )
            throw new DisException("请输入操作频道的ID！");

        $view = $_GET['view'] ? $_GET['view'] : $_POST['view'];
        $channel = DisChannelCtrl::channel($channel_id);

        if ( $view == 'tag-add' )
        {
            $val['tag_id'] = $channel->add_tag($_GET['tag']);
            $val['tag'] = $_GET['tag'];
        }
        elseif ( $view == 'tag-remove' )
        {
            $channel->remove_tag((int)$_GET['tag_id']);
            $val['tag_id'] = (int)$_GET['tag_id'];
        }
        elseif ( $view == 'save' )
        {
            $channel->update($_GET['name'], $_GET['desc'], $_GET['logo'], $_GET['domain']);
        }
        elseif ( $view == 'announce' )
        {
            $channel->edit_announce($_GET['announce']);
        }
        else
            throw new DisParamException('没有这种操作');

        $val['succeed'] = 1;
    }
    else if( isset($_GET['invite']) || $p == 'invite' )
    {
        $uname = $_GET['uname'];
        if( $uname )
            $tuid = DisUserCtrl::get_uid_by_name($uname);
        $email = $_GET['email'];
        if( $email )
            $tuid = DisUserCtrl::get_uid_by_email($email);

        if( $tuid )
        {
            DisNoticeCtrl::add_notice($tuid, 'invite', $channel_id, $_GET['content']);
        }
        else if ( $email )
        {
            if( !email_check($email) )
                throw new DisException('邮箱格式不正确');

            $user = DisUserCtrl::get_data($user_id);
            $channel = DisChannelCtrl::get_data($channel_id);

            $_url = 'http://'.DisConfigAttr::$app['url'].'/channel?id='.$channel_id;
            $titl = $user['username'].' 邀请你加入 '.DisConfigAttr::$app['name'].' 的频道';
            $offi = '<a href="'.$_url.'" target="_blank">'.$channel['name'].'</a>';
            $text = '<p>'.$user['username'].' 邀请你加入 '.$offi.' 频道</p><p>'
                .$_GET['content'].'</p><p><a href="'.$_url.'" target="_blank">'.$_url.'</a></p>';
            $desc = '<p>'.DisConfigAttr::$app['name'].'：'.DisConfigAttr::$app['desc'].'</p>';
            DisMailPlg::send_email($email, $titl, $text.$desc);
        }
        else
            throw new DisException("用户不存在！");
    }
    else if( $p == 'veri' )
    {
        $view = $_GET['view'] ? $_GET['view'] : $_POST['view'];
        if( $view == 'name' )
        {
            if( !isset($_GET['name']) || empty($_GET['name']) )
                throw new DisException('用户名空');
            $channel_id = DisChannelCtrl::get_id_by_name($_GET['name']);
            if( $channel_id > 0 )
                echo "该用户名已经被占用";
        }
        elseif( $view == 'domain' )
        {
            if( !isset($_GET['domain']) || empty($_GET['domain']) )
                throw new DisException('域名空');
            $channel_id = DisChannelCtrl::get_id_by_domain($_GET['domain']);
            if( $channel_id > 0 )
                throw new DisException("该域名已经被占用！");
        }
        else
            throw new DisException("请输入合法的用户名！");
    }
    else if( isset($_GET['list']) || $p == 'list' ) // 列出加入的频道
    {
        $cu = new DisChanUserCtrl($user_id);
        $channel_ids = $cu->list_joined_ids();
//        DisObject::print_array($channel_ids);

        $len = count($channel_ids);
        if( $_GET['count'] )
            $count = (int)$_GET['count'];
        else
            $count = $len;

        for( $i = 0; $i < $len && $i < $count; $i ++ )
            $channels[$i] = DisChannelCtrl::get_data($channel_ids[$i]);
        $val['channels'] = $channels;
    }
    else if( isset($_GET['create']) || $p == 'create' )
    {
        if( isset($_GET['name']) && !empty($_GET['name']) )
        {
            $channel = DisChannelCtrl::create_channel($user_id, $_GET['name'],
                    $_GET['type'], $_GET['desc'], $_GET['logo'], $_GET['tags']);
            DisObject::print_array($channel);
        }
        else
            throw new DisException("需要输入有效的频道名称！");
    }
    else
        throw new DisException("无效的操作类型！");
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();

echo $err;
//$val['msg'] = $err;
$val['id'] = $channel_id;
DisObject::print_array($val[channels]);
echo json_encode($val);
?>