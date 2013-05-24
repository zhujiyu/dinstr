<?php
/**
 * @package: DIS.INIT
 * @file   : DisChanTest.class.php
 * Description of DisChanTest
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

DisDBTable::query("delete from channels");

$str = "select count";
$len = DisDBTable::count($str);

?>
