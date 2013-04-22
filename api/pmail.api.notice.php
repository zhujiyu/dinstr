<?php
/**
 * @package: PMAIL.INTE.API
 * @file   : pmail.api.notice.php
 * 系统通知API
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
        throw new DisException("你尚未登录！");

    $p = $_REQUEST['p'] ? $_REQUEST['p']: 'notice';
    $user_id = $_SESSION['userId'];
    DisUserCtrl::set_inline($user_id);
    $notice = new DisNoticeCtrl($user_id);

    if( $p == 'init' )
    {
        $notice_ids = $notice->get_unread_notice_ids();
        $val['notices'] = $notice->parse_notice_ids($notice_ids);
    }
    else if( $p == 'refrush' )
    {
        $readed = $_GET['readed'] ? $_GET['readed'] : 0;
        $notice_ids = $notice->get_incr_notice_ids($readed);
        $val['notices'] = $notice->parse_notice_ids($notice_ids);
    }
    else if( $p == 'clear' )
    {
        $notice->clear_notices();
        $val['succeed'] = '1';
    }
    else if( $p == 'drop' )
    {
        $notice->remove_notices($_GET['notice_ids']);
        $val['succeed'] = '1';
    }
    else
        throw new DisException('错误的类型');

    if( $p == 'init' || $p == 'refrush' )
    {
        $param = DisUserParamCtrl::get_data($user_id);
        $val['msg'] = $param['msg_notice'];
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