<?php
/**
 * @package: DIS.TEST
 * @file   : DisUserTest.class.php
 * Description of DisUserTest
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
require_once dirname(__FILE__)."/../../common.inc.php";

class DisUserTest extends DisDataBaseTest
{
    function  __construct()
    {
        $sqls = array("
CREATE TABLE new_notices
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id int not null, -- 信息的所有者
    `type` enum('reply', 'follow', 'invite', 'approve', 'apply') default 'reply',
    data_id bigint default 0,
    message varchar(255),
    create_time timestamp,
    index (user_id, `type`, data_id)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000
        ", "
CREATE TABLE notices
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id int not null, -- 信息的所有者
    `type` enum('reply', 'follow', 'invite', 'approve', 'apply') default 'reply',
    data_id bigint default 0,
    message varchar(255),
    create_time timestamp,
    index (user_id, `type`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000
        ");

        $this->default_data_file = 'users.xml';
        parent::__construct($sqls);
    }

    function testInitNotice()
    {
        $notice = new DisNoticeCtrl(1234861);

        $param = new DisUserParamCtrl(1234861);
        $this->assertEquals(12, $param->attr('msg_notice'));
        $param->notice('msg_notice', 10);
        $this->assertEquals(22, $param->attr('atme_notice'));

        $this->assertTablesEqual($this->_getXmlTable('user_table_after_notice.xml'), $this->_getDatabaseTable());
        $param->notice('atme_notice', -20);
        $this->assertEquals(2, $this->mock->attr('atme_notice'));
        $param->notice('atme_notice', -20);
        $this->assertEquals(0, $param->attr('atme_notice'));

        $user_table = $this->_getXmlTable('user_table_after_notice.xml');
        $user_table->setValue(0, 'atme_notice', 0);
        $this->assertTablesEqual($user_table, $this->_getDatabaseTable());
    }

    /**
     * @expectedException soParamException
     */
    function testNoticeBadParamException()
    {
        $user = new DisUserCtrl(1234861);
        $user->notice('follow_notice', 10);
    }

//    function testUpdateNotice()
//    {
//        $this->mock->init(1234861);
//        $notices = $this->mock->update_notice();
//        $this->assertEquals(12, $notices['atme_notice']);
//    }
//
//    /**
//     * @expectedException soParamException
//     */
//    function testNoticeZeroParamException()
//    {
//        $this->mock->init(1234861);
//        $this->mock->notice('fans_notice', 0);
//    }
//
//    /**
//     * @expectedException soParamException
//     */
//    function testNoticeNotChangeException()
//    {
//        $this->mock->init(1234861);
//        $this->mock->notice('fans_notice', -10);
//    }
//
//    function testFreeze()
//    {
//        $this->mock->init(1234861);
//        $this->assertEquals(0, $this->mock->attr('freezed'));
//        $this->mock->freeze(128.50);
//        $this->assertEquals(128.50, $this->mock->attr('freezed'));
//
//        $user_table = $this->_getXmlTable('user_table.xml');
//        $user_table->setValue(1, 'freezed', 128.50);
//        $this->assertTablesEqual($user_table, $this->_getDatabaseTable());
//
//        $this->mock->init(1234861);
//        $this->assertEquals(128.50, $this->mock->attr('freezed'));
//    }
//
//    function testUnfreeze()
//    {
//        $this->mock->init(1234861);
//        $this->assertEquals(0, $this->mock->attr('freezed'));
//        $this->mock->freeze(128.50);
//        $this->mock->unfreeze(28.50);
//        $this->mock->init(1234861);
//        $this->assertEquals(100, $this->mock->attr('freezed'));
//        $this->mock->unfreeze(100);
//        $this->assertEquals(0, $this->mock->attr('freezed'));
//    }
//
//    function testUnfreezeExceptoin()
//    {
//        $this->mock->init(1234861);
//        $this->assertEquals(0, $this->mock->attr('freezed'));
//        $this->mock->freeze(128.50);
//
//        $exceptions = 0;
//        try
//        {
//            $this->mock->unfreeze(228.50);
//        }
//        catch (soParamException $ex)
//        {
//            $exceptions ++;
//        }
//        try
//        {
//            $this->mock->unfreeze(0);
//        }
//        catch (soParamException $ex)
//        {
//            $exceptions ++;
//        }
//        try
//        {
//            $this->mock->unfreeze(-228.50);
//        }
//        catch (soParamException $ex)
//        {
//            $exceptions ++;
//        }
//
//        if( $exceptions < 3 )
//            $this->fail("解冻失败！");
//    }
//
//    function testFinance()
//    {
//        $this->mock->init(1234861);
//        $this->assertEquals(0, $this->mock->attr('finance'));
//        $this->mock->finance(128.50);
//        $this->assertEquals(128.5, $this->mock->attr('finance'));
//    }
//
//    function testPay()
//    {
//        $this->mock->init(1234861);
//        $this->assertEquals(200, $this->mock->attr('imoney'));
//        $this->mock->freeze(128.50);
//        $this->mock->pay(128.50);
//        $this->mock->init(1234861);
//        $this->assertEquals(71.50, $this->mock->attr('imoney'));
//        $this->assertEquals(0, $this->mock->attr('freezed'));
//    }
//
//    /**
//     * @expectedException soParamException
//     */
//    function testPayException()
//    {
//        $this->mock->init(1234861);
//        $this->assertEquals(200, $this->mock->attr('imoney'));
//        $this->mock->freeze(120.50);
//        $this->mock->pay(128.50);
//    }
}

//    protected function _getDataSet($file)
//    {
//        $xml_dataset = parent::_getDataSet($file);
//        $xml_datatable = $xml_dataset->getTable('users');
//        $count = $xml_datatable->getRowCount();
//
//        for( $i = 0; $i < $count; $i ++ )
//        {
//            $value1 = md5(md5($xml_datatable->getValue($i, 'password')).md5($xml_datatable->getValue($i, 'salt')));
//            $xml_datatable->setValue($i, 'password', $value1);
//            $value2 = md5(md5($xml_datatable->getValue($i, 'impassword')).md5($xml_datatable->getValue($i, 'salt')));
//            $xml_datatable->setValue($i, 'impassword', $value2);
//        }
//        return $xml_dataset;
//    }

//    protected function getDataSet()
//    {
//        return $this->_getDataSet('user_table.xml');
//    }

//        $this->table = "users";
//        $this->columns = "ID, username, email, salt, domain, atme_notice, `password`, `impassword`, imoney";
//        $this->mock = new soUserDataTable();

//$file = "common.inc.php";
//for( $i = 0; $i < 5; $i ++ )
//{
//    if( file_exists($file) )
//    {
//        require_once ( $file );
//        break;
//    }
//    $file = "../".$file;
//}
?>
