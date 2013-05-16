<?php
/**
 * @package: DINSTR.API
 * @file   : user.api.php
 * 用户API
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 DIS(有向信息流)
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
require_once '../common.inc.php';

ob_start();
try
{
    $p = $_GET['p'] ? $_GET['p']: $_POST['p'];
    $view = $_GET['view'] ? $_GET['view'] : $_POST['view'];

    if( $p != 'veri' )
    {
        if( !isset($_SESSION['userId']) || $_SESSION['userId'] == 0 || !DisUserCtrl::check_inline($_SESSION['userId']) )
            throw new DisException("没有登录！");
    }
    $user_id = $_SESSION['userId'];

    if( $p == 'veri' )
    {
        if( $_GET['item'] == 'email' )
        {
            $target_id = DisUserCtrl::get_uid_by_email($_GET['email']);
            if( $target_id > 0 )
                echo "邮箱已注册，请直接登录";
        }
        else if( $_GET['item'] == 'uname' )
        {
            $target_id = DisUserCtrl::get_uid_by_name($_GET['uname']);
            if( $target_id > 0 )
                echo "用户昵称已经被占用";
        }
        else if( $_GET['itme'] == 'imagecode' )
        {
            if( strtoupper($_SESSION['ImageCode']) != strtoupper($_GET['code']) )
                echo '验证码错误';
        }
    }
    else if( $p == 'follow' || $p == 'cancel-follow' )
    {
        if( !isset($_GET['id']) || !is_string($_GET['id']) )
            throw new DisException("请输入要关注用户的ID！");

        $user = DisUserCtrl::user($user_id);
        $target_id = (int)$_GET['id'];

        if( $p == 'follow' )
            $user->follow($target_id);
        else if( $p == 'cancel-follow' )
            $user->cancel_follow($target_id);
    }
    else if( $p == 'edit' )
    {
        $info = $_GET ? $_GET : $_POST;
        $user = new DisUserCtrl($user_id);

        if( $view == 'info' )
        {
            $user->update($info);
        }
        else if( $view == 'pword' )
        {
            if( !$user->check_password($info['old_pword']) )
                throw new DisException('原密码错误！');
            $user->update_password($info['new_pword']);
//            $user->reset_pword($info['old_pword'], $info['new_pword']);
        }
//        elseif ( $view == 'tag-add' )
//        {
//            $tag_id = pmCtrlUser::add_tag($user_id, $_GET['tag']);
//            $val['tag_id'] = $tag_id;
//        }
//        elseif ( $view == 'tag-remove' )
//        {
//            pmCtrlUser::remove_tag($user_id, $_GET['tag_id']);
//        }
    }
    else if( $p == 'list' )
    {
        $user = DisUserCtrl::user($user_id);
        $user_ids = $user->list_follow_user_ids();
        $val['user_ids'] = $user_ids;
    }
    else if( $p == 'imoney' )
    {
        if( strtoupper($_SESSION['ImageCode']) == strtoupper($_GET['code']) )
        {
            try
            {
                $mn = new DisMoneyLogCtrl($user_id);
                $mn->recharge();
            }
            catch (DisException $exx)
            {
                $val['imoney_err'] = $exx->getMessage();
            }

            $param = DisUserParamCtrl::get_data($user_id);
            $val['imoney'] = $param['imoney'];
        }
        else
        {
            $val['code_err'] = '验证码错误';
        }
    }
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$val['msg'] = ob_get_contents();
ob_end_clean();

$val['id'] = $user_id;
$val['target_id'] = $target_id;
echo json_encode($val);
?>