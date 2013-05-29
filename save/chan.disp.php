<?php
/**
 * @package: DIS.INIT
 * @file   : chan.disp.php
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

//$c = DisChannelCtrl::channel($channel_id);

$chan1 = new DisChannelCtrl(100004);
DisObject::print_array($chan1->info());
$chan2 = new DisChannelCtrl(100005);
DisObject::print_array($chan2->info());
?>
