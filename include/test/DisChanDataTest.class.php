<?php
/**
 * @package: DIS.TEST
 * @file   : DisChanDataTest.class.php
 * Description of DisChanDataTest
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
$file = "common.inc.php";
for( $i = 0; $i < 5; $i ++ )
{
    if( file_exists($file) )
    {
        require_once ( $file );
        break;
    }
    $file = "../$file";
}

class DisChanDataTest extends DisDataBaseTest
{
    function  __construct()
    {
        parent::__construct();
        $this->default_data_file = "channels.xml";

//        $this->columns = "ID, name, logo, `type`, description";
//        $this->table = "channels";
//        $this->mock = new DisChannelData();

        $str = "
CREATE TABLE channels
(
    ID int AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(32),
    logo bigint default 0,
    `type` enum('social', 'business', 'info', 'news') default 'social', --  社会交往social 商品交易business 商务资讯info 社会新闻news
    description varchar(256), -- 介绍
    announce varchar(512),    -- 公告
    creater int, -- 创建者
    -- 参数
    member_num int default 0,
    subscriber_num int default 0,
    info_num bigint default 0,
    applicant_num int default 0,
    create_time int,
    unique key (`name`),
    index (creater),
    index (create_time)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000
";
        $this->pdo->exec($str);

    }

    function testLoadData()
    {
        $mock = new DisChannelData();
        $mock->init(1234861);
        $this->assertEquals('游戏海报', $mock->attr('name'));
        $mock->init(1593490);
        $this->assertEquals('这副海报是新的 高战 ', $mock->attr('description'));

//        $this->mock->init(1234861);
//        $this->assertEquals('游戏海报', $this->mock->attr('name'));
//        $this->mock->init(1593490);
//        $this->assertEquals('这副海报是新的 高战 ', $this->mock->attr('description'));
//        $r1 = $this->mock->name_exist('测试帐号');
//        $this->assertTrue($r1);
//        $r2 = $this->mock->name_exist('新加网寨');
//        $this->assertTrue(!$r2);
    }

//    protected function getDataSet()
//    {
//        return $this->_getDataSet('channels.xml');
//    }

//    protected function _getDataSet($file)
//    {
//        $xml_dataset = $this->createFlatXMLDataSet(dirname(__FILE__).'/_files/'.$file);
//        if( $xml_dataset == null )
//            return NULL;
//        $xml_datatable = $xml_dataset->getTable('channels');
//        if( $xml_datatable == null )
//            return NULL;
//
//        $count = $xml_datatable->getRowCount();
//        for ( $i = 0; $i < $count; $i ++ )
//        {
//            $value = md5(md5($xml_datatable->getValue($i, 'password')).md5($xml_datatable->getValue($i, 'salt')));
//            $xml_datatable->setValue($i, 'password', $value);
//        }
//        return $xml_dataset;
//    }

//    function testInsert()
//    {
//        $this->mock->insert(1234861, '新加网寨', md5('121981'), 'gou86@sina.cn');
//        $table = $this->_getXmlTable('office_after_insert.xml');
//        $table->setValue(3, 'salt', $salt);
//        $table->setValue(3, 'password', md5(md5('121981').md5($salt)));
//        $this->assertTablesEqual($table, $this->_getDatabaseTable());
//        $r = $this->mock->name_exist('新加网寨');
//        $this->assertTrue($r);
//    }
}
?>
