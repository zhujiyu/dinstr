<?php
/**
 * @file : DisConfigAttr.class.php
 *
 * DIS系统常量表
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

function init_smarty()
{
    $gSmarty = new Smarty;
    $gSmarty->config_dir   = DIS_ROOT.'configs/';
    $gSmarty->cache_dir    = DIS_ROOT.'cache/';
    $gSmarty->template_dir = DIS_ROOT.'templates/';
    $gSmarty->compile_dir  = DIS_ROOT.'templates_c/';
//    $gSmarty->compile_check = true;

    $gSmarty->assign("app", DisConfigAttr::$app);
    $gSmarty->assign("comp", DisConfigAttr::$comp);
    return $gSmarty;
}

try
{
    DisDBTable::$readPDO  = new PDO("mysql:host=".DisConfigAttr::$dbread ['host'].";dbname=".DisConfigAttr::$dbread ['dbname'],
            DisConfigAttr::$dbread ['username'], DisConfigAttr::$dbread ['password']);
    DisDBTable::$writePDO = new PDO("mysql:host=".DisConfigAttr::$dbwrite['host'].";dbname=".DisConfigAttr::$dbwrite['dbname'],
            DisConfigAttr::$dbwrite['username'], DisConfigAttr::$dbwrite['password']);

    DisVectorCache::$_memcached = new DisMemcachedMock();
    DisRowCache::$_memcached    = new DisMemcachedMock();
}
catch (Exception $ex)
{
    echo $ex->getTrace();
}

session_start();

//DisDBTable::$readPDO  = new PDO('mysql:host=localhost;dbname=dinstr', 'jiyu', 'jiyu');
//DisDBTable::$writePDO = new PDO('mysql:host=localhost;dbname=dinstr', 'jiyu', 'jiyu');

//    $memcache = new Memcache;
//    $memcache->connect('127.0.0.1', 11211) or die ("Could not connect");
//    $version = $memcache->getVersion();
//    echo "Server's version: ".$version."\n";

//MemcachedClient client1 = new MemcachedClient(new InetSocketAddress("192.168.2.9",11211));
//DisRowCache::$_memcached = memcache_pconnect('localhost', 11211);
//wget http://memcached.googlecode.com/files/memcached-1.4.5.tar.gz
//DisRowCache::$_memcached = memcache_connect(DisConfigAttr::$row_memcached['host'],
//            DisConfigAttr::$row_memcached['port']);

//if( !DisDBTable::$readPDO )
//{
//    DisDBTable::$readPDO = new DisMysqlAdapter('mysql:host='.DisConfigAttr::$dbread['host'].';dbname='.DisConfigAttr::$dbread['dbname'],
//            DisConfigAttr::$dbread['username'], DisConfigAttr::$dbread['password']);
//}
//if( !DisDBTable::$writePDO )
//{
//    DisDBTable::$writePDO = new DisMysqlAdapter('mysql:host='.DisConfigAttr::$dbwrite['host'].';dbname='.DisConfigAttr::$dbwrite['dbname'],
//            DisConfigAttr::$dbwrite['username'], DisConfigAttr::$dbwrite['password']);
//}

//    public static $guest_chans = array
//    (
//        'ids'=>array(100040, 100041, 100044, 100045, 100043),
//        'weights'=>array(1, 1, 1, 1, 1),
//        'ranks'=>array(5, 4, 3, 2, 1),
//    );

//function start_phpunit_test()
//{
//    DisVectorCache::$_memcached = new pmMemcachedMock();
//    DisRowCache::$_memcached = new pmMemcachedMock();
//    DisDBTable::$readPDO = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');
//    DisDBTable::$writePDO = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');
//}
?>