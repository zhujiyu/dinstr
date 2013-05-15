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
require_once "../../common.inc.php";

class DisUserTest extends DisDataBaseTest
{
    function  __construct()
    {
        parent::__construct();
        $this->default_data_file = 'users.xml';

        $sqls = array("drop table users","
CREATE TABLE users
(
    -- 用户核心信息，其中ID，username,email都可用于登录
    ID int AUTO_INCREMENT PRIMARY KEY,
    email varchar(255), -- 邮箱注册 安全邮箱，用于找回密码，也可用于登录
    username varchar(32),
    avatar bigint default 0, -- 头像
    -- 安全设置
    salt char(5),
    `password` char(32), -- 用md5算法将密码转成32位
    impassword char(32),  -- 资金帐号密码，支付密码
    check_errs tinyint default 0, -- 资金密码输入错误的次数，6次错误则锁定一小时
    last_check int default 0, -- 最后一次密码检验时间，用于设置密码锁定一小时
    -- 个人基本信息
    sign varchar(64),  -- 个性签名 一个好的个人签名，可以获得更多的信任
    introducer int default 0,
    `rank` smallint default 0, -- 级别
    live_city varchar(64),
    self_intro varchar(255), -- 个人介绍
    gender enum('none', 'male', 'female') default 'none',
    contact varchar(255), -- 详细联系方式
    ID_type varchar(10), -- 身份证件类型
    ID_number varchar(25), -- 证件号码
    regis_time timestamp,
    -- 配置
    msg_setting enum('all', 'follow', 'channeler', 'none') default 'all',
    -- 索引设置
    unique (`email`),
    index (username)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000000
        ", "
CREATE TABLE user_params
(
    ID int PRIMARY KEY,
    -- 个人基本信息
    imoney float default 0,
    online_times bigint default 0,
    -- 统计参数
    join_num int default 0,    -- 加入的频道数
    subscribe_num int default 0,
    applicant_num int default 0, -- 正在处理的加入频道申请的数目
    create_num int default 0,  -- 创建频道数
    head_num int default 0,    -- 信息头数
    note_num int default 0,    -- 信息数
    draft_num int default 0,   -- 草稿数
    collect_num int default 0, -- 收藏邮件数量
    interest_num int default 0,
    approved_num int default 0, -- 参与邮件主题活动数
    msg_num int default 0,    -- 发送私信数
    follow_num int default 0, -- 关注人数
    fans_num int default 0,   -- 粉丝数
    -- 消息提醒
    reply_notice int default 0,
    note_notice int default 0,
    msg_notice int default 0,
    system_notice int default 0,
    fans_notice int default 0
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000000
        ");

        $len = count($sqls);
        for( $i = 0; $i < $len; $i ++ )
            $this->pdo->exec($sqls[$i]);

    }

    protected function _getDataSet($file)
    {
        $xml_dataset = parent::_getDataSet($file);
        $xml_datatable = $xml_dataset->getTable('users');
        $count = $xml_datatable->getRowCount();

        for( $i = 0; $i < $count; $i ++ )
        {
            $value1 = md5(md5($xml_datatable->getValue($i, 'password')).md5($xml_datatable->getValue($i, 'salt')));
            $xml_datatable->setValue($i, 'password', $value1);
            $value2 = md5(md5($xml_datatable->getValue($i, 'impassword')).md5($xml_datatable->getValue($i, 'salt')));
            $xml_datatable->setValue($i, 'impassword', $value2);
        }
        return $xml_dataset;
    }

    function testInit()
    {
        $user = new DisUserCtrl(1234861);
        $this->assertEquals("帐号1234861", $user->attr('username'));
        $user->init('zhujiyu@abc.com');
        $this->assertEquals("zhujiyu", $user->attr('username'));
    }

    function testInitBadUser()
    {
        $user = new DisUserCtrl(12340);
        $this->assertEquals(0, $user->ID);
    }

    function testCheckPassword()
    {
        $user = new DisUserCtrl(1593490);
        $r = $user->check_password(md5('kuke.com'));
        $this->assertTrue($r);
    }

    function testCheckIMPassword()
    {
        $user = new DisUserCtrl(1593490);
        for( $i = 0; $i < 4; $i ++ )
        {
            $user->check_password(md5('kuke.com'), 'imoney');
        }
        $user->init(1593490, "ID, username, sign, live_city, contact, self_intro, check_errs");
        $this->assertEquals(4, $user->attr('check_errs'));

        $r1 = $user->check_password(md5('gou86.com'), 'imoney');
        $this->assertTrue($r1);
        $user->init(1593490, "ID, username, sign, live_city, contact, self_intro, check_errs");
        DisObject::print_array($user->info());
        $this->assertEquals(0, $user->attr('check_errs'));
    }

    /**
     * @expectedException DisPWException
     */
    function testCheckIMPasswordException()
    {
        $user = new DisUserCtrl(1593490);
        for( $i = 0; $i < 6; $i ++ )
        {
            $user->check_password(md5('kuke.com'), 'imoney');
        }
    }

//    function testUpdatePassword()
//    {
//        $this->mock->init(1593490);
//        $r1 = $this->mock->update_password(md5('kuke.com'));
//        $this->assertTrue(!$r1);
//        $r2 = $this->mock->update_password(md5('kuuuke.com'));
//        $this->assertTrue($r2);
//
//        $user_table = $this->_getXmlTable('user_table.xml');
//        $user_table->setValue(1, 'password', md5(md5('kuuuke.com').md5($user_table->getValue(1, 'salt'))));
//        $this->assertTablesEqual($user_table, $this->_getDatabaseTable());
//    }
//
//    function testUpdateIMPassword()
//    {
//        $this->mock->init(1593490);
//        $r1 = $this->mock->update_password(md5('gou86.com'), 'imoney');
//        $this->assertTrue(!$r1);
//        $r2 = $this->mock->update_password(md5('kuuuke.com'), 'imoney');
//        $this->assertTrue($r2);
//
//        $user_table = $this->_getXmlTable('user_table.xml');
//        $user_table->setValue(1, 'impassword', md5(md5('kuuuke.com').md5($user_table->getValue(1, 'salt'))));
//        $this->assertTablesEqual($user_table, $this->_getDatabaseTable());
//    }
//
//    /**
//     * @expectedException soParamException
//     */
//    function testNoticeBadParamException()
//    {
//        $user = new DisUserCtrl(1234861);
//        $user->notice('follow_notice', 10);
//    }
//
//    function testUpdateNotice()
//    {
//        $this->mock->init(1234861);
//        $notices = $this->mock->update_notice();
//        $this->assertEquals(12, $notices['atme_notice']);
//    }
//
//    function testNotice()
//    {
//        $this->mock->init(1234861);
//        $this->assertEquals(12, $this->mock->attr('atme_notice'));
//        $this->mock->notice('atme_notice', 10);
//        $this->assertEquals(22, $this->mock->attr('atme_notice'));
//        $this->assertTablesEqual($this->_getXmlTable('user_table_after_notice.xml'), $this->_getDatabaseTable());
//        $this->mock->notice('atme_notice', -20);
//        $this->assertEquals(2, $this->mock->attr('atme_notice'));
//        $this->mock->notice('atme_notice', -20);
//        $this->assertEquals(0, $this->mock->attr('atme_notice'));
//
//        $user_table = $this->_getXmlTable('user_table_after_notice.xml');
//        $user_table->setValue(0, 'atme_notice', 0);
//        $this->assertTablesEqual($user_table, $this->_getDatabaseTable());
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
//    function testInsertUser()
//    {
//        global $salt;
//        $this->mock->insert('朱继玉', md5('332288'), md5('55332288'));
//        $this->assertEquals('朱继玉', $this->mock->attr('username'));
//
//        $user_table = $this->_getXmlTable('user_table_after_insert.xml');
//        $user_table->setValue(3, 'salt', $salt);
//        $user_table->setValue(3, 'password', md5(md5('332288').md5($salt)));
//        $user_table->setValue(3, 'impassword', md5(md5('55332288').md5($salt)));
//        $this->assertTablesEqual($user_table, $this->_getDatabaseTable());
//
//        $this->mock->init(1593649);
//        $r = $this->mock->check_password(md5('55332288'), 'imoney');
//        $this->assertTrue($r);
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
//
//    function testParam()
//    {
//        $this->mock->init(1234861);
//        $this->assertEquals(0, $this->mock->attr('fans_num'));
//        $this->mock->increase('fans_num');
//        $this->assertEquals(1, $this->mock->attr('fans_num'));
//    }
}

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
