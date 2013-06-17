<?php
/**
 * DINSTR项目 php文件 v1.0.0
 * 个人首页
 *
 * @author   : 朱继玉<zhuhz82@126.com>
 * @Copyright: 2013 DIS(有向信息流)
 * @Date     : 2013-04-16
 * @encoding : UTF-8
 * @version  : 1.0.0
 */
require_once 'common.inc.php';

$gSmarty = init_smarty();
ob_start();

try
{
    $user = DisUserCtrl::user(10000);
    $gSmarty->assign("user", $user->info());

}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();
$gSmarty->assign("err", $err);

$gSmarty->assign("title", "发信息");
$gSmarty->display("page/edit.tpl");
?>
