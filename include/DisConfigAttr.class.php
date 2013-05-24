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

class DisConfigAttr
{
    public static $app = array('name'=>'海报板', 'version'=>'1.0', 'status'=>'内测',
        'goal'=>"无噪音的实用资讯", 'locate'=>'定向有序资讯引擎', //'面向大众的精准化网络资讯平台',
        'logo'=>'css/logo/haibao.png', 'icon'=>'css/logo/haibao.png',
        'url'=>'tianezhen.com', 'icp'=>'京ICP备12012934号-1',
        'keywords'=>'公众邮件，邮件系统，资讯平台，资讯，商务，精准化，主动式，信息技术，信息检索，智能，信息流技术',
        'desc'=>'天鹅镇是一款定向信息流引擎，
            将每一条信息按照优先级，从特定信息源，发送到特定的接受目标人群，实现信息主次分明方向明确的流动。
            让人们从浩如烟海的垃圾信息中解脱出来，时刻掌握住与自身利益休戚相关的资讯。让有用的信息主动找到你。
            天鹅镇的最高目标是实现互联网世界里智能的信息流动。
            面向大众的精准化网络资讯平台：让重要的信息找到需要它的人，让每个人都有代表他所属的群体发言的机会，
            通过公共邮件频道，轻松地把资讯发送给最恰当的用户群，同时帮每个人轻松获取他最感兴趣的资讯！');
    public static $comp = array('name'=>'天鹅镇资讯系统', 'copyright'=>'Copyright @2013');

    // 读写分离，用一台服务器记录及时更新，用多台服务器负责读取
    public static $dbread = array('host'=>'localhost', 'username'=>'jiyu', 'password'=>'jiyu',
        'dbname'=>'dinstr', 'connect'=>1);
    public static $dbwrite = array('host'=>'localhost', 'username'=>'jiyu', 'password'=>'jiyu',
        'dbname'=>'dinstr', 'connect'=>1);

    public static $taobaoAPI = array('url'=>'http://gw.api.taobao.com/router/rest?',
        'key'=>'12176383', 'secret'=>'916193de4c10e511cf141363aac4adf0', 'nick'=>'zhujiyuhappy',
        'signMethod'=>'HmacMD5');
    public static $taobaoTestAPI = array('url'=>'http://gw.api.tbsandbox.com/router/rest?',
        'key'=>'test', 'secret'=>'test', 'nick'=>'sandbox_c_2', 'signMethod'=>'HmacMD5');

    public static $vector_memcached = array('host'=>'localhost', 'port'=>11211);
    public static $row_memcached = array('host'=>'localhost', 'port'=>11211);

    public static $intervals = array('five'=>300, 'quarter'=>900, 'hour'=>3600,
        'day'=>86400, 'week'=>604800); // 单位秒
    public static $periods = array('3h'=>10800, '6h'=>21600, '1d'=>86400, '3d'=>259200,
        'week'=>604800, 'month'=>2592000);

    public static $autoLoad = array
    (
        'DisObject'        =>'include/core/DisObject.class.php',
        'DisException'     =>'include/core/DisException.class.php',
        'DisDBException'   =>'include/core/DisException.class.php',
        'DisParamException'=>'include/core/DisException.class.php',
        'DisPWException'   =>'include/core/DisException.class.php',

        'DisMysqlAdapter'  =>'include/mysql/DisMysqlAdapter.class.php',
        'DisDBTable'       =>'include/mysql/DisDBTable.class.php',
        'DisDBStaticTable' =>'include/mysql/DisDBStaticTable.class.php',
        'DisMysqlStatement'=>'include/mysql/DisMysqlStatement.class.php',

        'DisMemcached'   =>'include/cache/DisMemcached.class.php',
        'DisVectorCache' =>'include/cache/DisVectorCache.class.php',
        'DisRowCache'    =>'include/cache/DisRowCache.class.php',

        'DisDataBaseTest' =>'include/test/core/DisDataBaseTest.class.php',
        'DisMemcachedMock'=>'include/test/core/DisMemcachedMock.class.php',

        'DisFeedVectorCache' =>'include/cache/DisFeedVectorCache.class.php',
        'DisUserVectorCache' =>'include/cache/DisUserVectorCache.class.php',
        'DisUserDataCache'   =>'include/cache/DisUserDataCache.class.php',
        'DisNoteVectorCache' =>'include/cache/DisNoteVectorCache.class.php',
        'DisNoteDataCache'   =>'include/cache/DisNoteDataCache.class.php',
        'DisChanVectorCache' =>'include/cache/DisChanVectorCache.class.php',
        'DisChanDataCache'   =>'include/cache/DisChanDataCache.class.php',

        'DisUserData'        =>'include/data/DisUserData.class.php',
        'DisUserParamData'   =>'include/data/DisUserParamData.class.php',
        'DisUserRelationData'=>'include/data/DisUserRelationData.class.php',
        'DisUserCtrl'        =>'include/ctrl/DisUserCtrl.class.php',
        'DisUserParamCtrl'   =>'include/ctrl/DisUserParamCtrl.class.php',
        'DisUserRelationCtrl'=>'include/ctrl/DisUserRelationCtrl.class.php',

        'DisUserLoginData'=>'include/data/DisUserLoginData.class.php',
        'DisMoneyLogData' =>'include/data/DisMoneyLogData.class.php',
        'DisUserLoginCtrl'=>'include/ctrl/DisUserLoginCtrl.class.php',
        'DisMoneyLogCtrl' =>'include/ctrl/DisMoneyLogCtrl.class.php',

        'DisChannelData'      =>'include/data/DisChannelData.class.php',
        'DisChanUserData'     =>'include/data/DisChanUserData.class.php',
        'DisChanApplicantData'=>'include/data/DisChanApplicantData.class.php',
        'DisChanTagData'      =>'include/data/DisChanTagData.class.php',
        'DisChannelCtrl'      =>'include/ctrl/DisChannelCtrl.class.php',
        'DisChanUserCtrl'     =>'include/ctrl/DisChanUserCtrl.class.php',
        'DisChanApplicantCtrl'=>'include/ctrl/DisChanApplicantCtrl.class.php',

        'DisGoodData'    =>'include/data/DisGoodData.class.php',
        'DisPhotoData'   =>'include/data/DisPhotoData.class.php',
        'DisPhotoTagData'=>'include/data/DisPhotoTagData.class.php',
        'DisGoodCtrl' =>'include/ctrl/DisGoodCtrl.class.php',
        'DisPhotoCtrl'=>'include/ctrl/DisPhotoCtrl.class.php',

        'DisInfoHeadData'  =>'include/data/DisInfoHeadData.class.php',
        'DisInfoUserData'  =>'include/data/DisInfoUserData.class.php',
        'DisInfoNoteData'  =>'include/data/DisInfoNoteData.class.php',
        'DisInfoGoodData'  =>'include/data/DisInfoGoodData.class.php',
        'DisInfoPhotoData' =>'include/data/DisInfoPhotoData.class.php',
        'DisInfoReplyData' =>'include/data/DisInfoReplyData.class.php',

        'DisNoteCtrl'    =>'include/ctrl/DisNoteCtrl.class.php',
        'DisHeadCtrl'    =>'include/ctrl/DisHeadCtrl.class.php',
        'DisInfoUserCtrl'=>'include/ctrl/DisInfoUserCtrl.class.php',

        'DisNoteKeywordData'=>'include/data/DisNoteKeywordData.class.php',
        'DisStreamData'=>'include/data/DisStreamData.class.php',
        'DisStreamCtrl'=>'include/ctrl/DisStreamCtrl.class.php',

//        'pmDataMailCollect' =>'include/data/pmDataMailExt.php',
//        'pmCtrlMailCollect' =>'include/ctrl/pmCtrlMailExt.php',
//        'pmDataMailCollectTag'=>'include/data/pmDataMailExt.php',

        'DisValueData'    =>'include/data/DisValueData.class.php',
        'DisFeedData'     =>'include/data/DisFeedData.class.php',
        'DisGuestFeedData'=>'include/data/DisGuestFeedData.class.php',
        'DisValueCtrl'    =>'include/ctrl/DisValueCtrl.class.php',
        'DisFeedCtrl'     =>'include/ctrl/DisFeedCtrl.class.php',
        'DisGuestFeedCtrl'=>'include/ctrl/DisGuestFeedCtrl.class.php',

        'DisNoticeData'=>'include/data/DisNoticeData.class.php',
        'DisNoticeCtrl'=>'include/ctrl/DisNoticeCtrl.class.php',

        'DisMessageData'    =>'include/data/DisMessageData.class.php',
        'DisMessageFormData'=>'include/data/DisMessageFormData.class.php',
        'DisMessageUserData'=>'include/data/DisMessageUserData.class.php',
        'DisMessageCtrl'    =>'include/ctrl/DisMessageCtrl.class.php',

        'SphinxClient'=>'include/plugin/SphinxClient.class.php',
        'DisMailPlg'  =>'include/plugin/DisMailPlg.class.php',
        'DisImagePlg' =>'include/plugin/DisImagePlg.class.php',
        'DisSearchPlg'=>'include/plugin/DisSearchPlg.class.php',
        'DisTaobaoPlg'=>'include/plugin/DisTaobaoPlg.class.php',

        'DisInviteData'  =>'include/data/DisInviteData.class.php',
        'DisFeedbackData'=>'include/data/DisFeedbackData.class.php',
        'DisInviteCtrl'  =>'include/ctrl/DisInviteCtrl.class.php',
        'DisFeedbackCtrl'=>'include/ctrl/DisFeedbackCtrl.class.php',
    );
}

// /usr/local/bin/memcached -d -m 1024 -u root -l 192.168.13.236 -p 12001 -c 256 -P /tmp/chenxinhan/memcached.pid
try
{
//    $memcache = new Memcache;
//    $memcache->connect('127.0.0.1', 11211) or die ("Could not connect");
//    $version = $memcache->getVersion();
//    echo "Server's version: ".$version."\n";

//MemcachedClient client1 = new MemcachedClient(new InetSocketAddress("192.168.2.9",11211));
//DisRowCache::$_memcached = memcache_pconnect('localhost', 11211);
//wget http://memcached.googlecode.com/files/memcached-1.4.5.tar.gz
//DisRowCache::$_memcached = memcache_connect(DisConfigAttr::$row_memcached['host'],
//            DisConfigAttr::$row_memcached['port']);
DisDBTable::$readPDO  = new PDO("mysql:host=".DisConfigAttr::$dbread ['host'].";dbname=".DisConfigAttr::$dbread ['dbname'], DisConfigAttr::$dbread ['username'], DisConfigAttr::$dbread ['password']);
DisDBTable::$writePDO = new PDO("mysql:host=".DisConfigAttr::$dbwrite['host'].";dbname=".DisConfigAttr::$dbwrite['dbname'], DisConfigAttr::$dbwrite['username'], DisConfigAttr::$dbwrite['password']);
}
catch (Exception $ex)
{
echo $ex->getTrace();
}
DisVectorCache::$_memcached = new DisMemcachedMock();
DisRowCache::$_memcached    = new DisMemcachedMock();
//DisDBTable::$readPDO  = new PDO('mysql:host=localhost;dbname=dinstr', 'jiyu', 'jiyu');
//DisDBTable::$writePDO = new PDO('mysql:host=localhost;dbname=dinstr', 'jiyu', 'jiyu');

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
