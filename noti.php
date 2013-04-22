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
    if( !isset($_SESSION['userId']) || $_SESSION['userId'] == 0 || !DisUserCtrl::check_inline($_SESSION['userId']) )
        throw new DisException('没有登录！');

    $user_id = $_SESSION['userId'];
    $user = DisUserCtrl::user($user_id);
    $gSmarty->assign("user", $user->info());
    DisUserCtrl::set_inline($user_id);

    $p = $_GET['p'] ? $_GET['p'] : $_POST['p'];
    $file = "pmail.notice.tpl";
    $notice = new DisNoticeCtrl($user_id);

    if( $_GET['all'] || $p == 'all' )
    {
        $view = 'all';
        $notices = $notice->list_all_notices(0);
        $notice_list = array();
        $len = count($notices);

        for( $i = 0; $i < $len; $i ++ )
        {
            try
            {
                $_notice = $notice->_parse($notices[$i]);
//                $notice = $this->_parse(self::get_data($notices[$i]));
            }
            catch ( DisException $ex )
            {
                $ex->trace_stack();
                continue;
            }
            $notice_list[] = $_notice;
        }
    }
    else
    {
        $view = 'unread';
        $notice_ids = $notice->get_unread_notice_ids();
        $notice_list = $notice->parse_notice_ids($notice_ids);
    }

    $gSmarty->assign("notices", $notice_list);
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();

$gSmarty->assign("err", $err);
$gSmarty->assign("view", $view);
$gSmarty->assign("title", '通知');
$gSmarty->display("pages/$file");
?>