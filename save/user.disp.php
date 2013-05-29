<?php
/**
 * @package: DIS.INIT
 * @file   : user.disp.php
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

$user = new DisUserCtrl('zhuhz82@126.com');
//DisObject::print_array($user);
DisObject::print_array($user->info());
$user->init('zhujiyu.tez@qq.com');
DisObject::print_array($user->info());
?>
