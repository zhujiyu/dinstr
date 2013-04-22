<?php
/**
 * @package: PMAIL.INTE.API
 * @file   : pmail.api.theme.php
 * 邮件主题API
 * @author    : zhujiyu , zhujiyu@139.com
 * @Copyright : 2012 公众邮件网
 * @Date      : 2012-4-11
 * @encoding  : UTF-8
 * @version   : 2.4.11
 */
require_once '../common.inc.php';

ob_start();
try
{
    if( !isset($_SESSION['userId']) || $_SESSION['userId'] == 0 || !DisUserCtrl::check_inline($_SESSION['userId']) )
        throw new DisException("没有登录！");
    $user_id = $_SESSION['userId'];

    $theme = DisHeadCtrl::theme((int)$_GET['theme_id']);
    if( !$theme || !$theme->ID )
        throw new DisException("没有要操作的数据！");
    $p = $_GET['p'] ? $_GET['p']: $_POST['p'];

    if( $p == 'interest' )
    {
        $theme->interest($user_id);
        $msg = "关注成功！";
    }
    else if( $p == 'cancel-interest' )
    {
        $theme->cancel_interest($user_id);
        $msg = "取消关注成功！";
    }
    else if( $p == 'approve' )
    {
        $theme->approve($user_id);
        $msg = "设置参与成功！";
    }
    else if( $p == 'cancel-approve' )
    {
        $theme->cancel_approve($user_id);
        $msg = "取消参与成功！";
    }
    else
        throw new DisException('无效的操作类型。');
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();

$val = array('err'=>$err, 'msg'=>$msg);
echo json_encode($val);
?>