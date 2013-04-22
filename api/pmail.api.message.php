<?php
/**
 * @package: PMAIL.INTE.API
 * @file   : pmail.api.message.php
 * 私信API
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

    $p = $_REQUEST['p'] ? $_REQUEST['p']: 'add';
    $val = array();
    $user_id = $_SESSION['userId'];

    if( $p == 'add' )
    {
        $reciever_id = 0;
        $relation_id = $_GET['relation'];
        if( $_GET['reciever'] )
            $reciever_id = DisUserCtrl::get_user_id($_GET['reciever']);
        else
            $reciever_id = DisMessageCtrl::get_friend($relation_id);
        if( $reciever_id == 0 )
            throw new DisException('该用户不存在！');

        $val['message'] = DisMessageCtrl::send($user_id, $reciever_id, $_GET['content'], $_GET['relation']);
        $val['friend'] = DisUserCtrl::get_data($reciever_id);
    }
    else if( $p == 'delete' )
    {
        if( !$_GET['relation'] )
            throw new DisException('输入参数不足！');
        if( $_GET['message'] )
            DisMessageCtrl::delete($_GET['message'], $_GET['relation']);
        else
            DisMessageCtrl::remove_messages($_GET['relation']);
    }
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$msg = ob_get_contents();
ob_end_clean();

$val['err'] = $msg;
echo json_encode($val);
?>