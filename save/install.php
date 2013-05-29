<?php
/**@package: DIS.INIT
 * @file   : install.php
 *
 * @author   : 朱继玉<zhuhz82@126.com>
 * @Copyright: 2013 有向信息流
 * @Date     : 2013-04-16
 * @encoding : UTF-8
 * @version  : 1.0.0
 */
require_once '../../common.inc.php';

echo "<pre>";
echo "initialize...\n\n";
require 'user.init.php';
include "chan.init.php";
echo "</pre>";

//echo '21';
//echo "initialize user data...\n";
//echo "initialize channel data...\n";
//try
//{
//    $con = mysql_pconnect('localhost', 'jiyu', 'jiyu');
//    echo "con id: $con";
//}
//catch (Exception $ex)
//{
//    echo $ex->getTrace();
//}

//echo md5('bieyiweininengcaodaoshouquanma');
// md5('bieyiweininengcaodaoshouquanma') 776360110ec1e58734587709d8cf4f25
//if( !isset($_GET['auth']) || $_GET['auth'] != md5('bieyiweininengcaodaoshouquanma') )
//{
//    echo "<br>授权码不正确";
//    return;
//}
//echo '22';
//
//require_once "/usr/share/php/PHPUnit/Util/PDO.php";
//DisDBTable::$readPDO  = new PDO('mysql:host=localhost;dbname=dinstr', 'jiyu', 'jiyu');
//DisDBTable::$writePDO = new PDO('mysql:host=localhost;dbname=dinstr', 'jiyu', 'jiyu');
?>
