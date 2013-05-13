<?php
/**
 * @package: DIS.TEST.MYSQL
 * @file   : DisUserDataTest.class.php
 * Description of DisUserDataTest
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
    $file = "../".$file;
}

class DisUserDataTest extends DisDataBaseTest
{
    function  __construct()
    {
        parent::__construct();

        $this->table = "users";
        $this->columns = "ID, username, email, salt, domain, atme_notice, `password`, `impassword`, imoney";
        $this->mock = new soUserDataTable();

        $str = "
CREATE TABLE users
(
    -- 用户核心信息，其中ID，username,email都可用于登录
    ID int AUTO_INCREMENT PRIMARY KEY,
    username varchar(32) not null,
    email varchar(255), -- 安全邮箱，用于找回密码，也可用于登录
    imoney float default 0,
    freezed float default 0, -- 冻结的资金
    avatar varchar(255), -- 头像
    -- 安全设置
    salt char(5) not null,
    `password` char(32) not null, -- 用md5算法将密码转成32位
    impassword char(32) not null, -- 资金帐号密码
    errs tinyint default 0, -- 资金密码输入错误的次数，6次错误则锁定一小时
    last_pw_check int default 0,
    -- 个人基本信息
    phone varchar(15),
    realname varchar(32),
    gender enum('none', 'male', 'female') default 'none',
    ID_type varchar(10), -- 身份证件类型
    ID_number varchar(25), -- 证件号码
    self_intro varchar(255), -- 个人介绍
    live_city varchar(64),
    introducer int default 0,
    contact varchar(255),
    -- 个人参数
    `domain` varchar(32), -- 个性域名
    `rank` smallint default 0, -- 级别
    `online` tinyint default 0,
    online_times bigint default 0,
    last_login timestamp,
    -- 统计参数
    flighty_num int default 0, -- 撒娇（将心愿或者文章发给特定个人）的次数
    fans_num int default 0, -- 粉丝数
    follow_num int default 0, -- 关注人数
    blog_num int default 0, -- 微博条数
    wish_num int default 0, -- 发布心愿次数
    action_num int default 0, -- 参加活动次数
    finance float default 0, -- 完成的融资数量
    suggest_num int default 0, -- 发送评论数
    comment_num int default 0, -- 发送评论数
    favorite_num int default 0, -- 收藏资讯数量
    create_office_num int default 0, -- 创建的会社数
    join_office_num int default 0, -- 加入的会社数
    -- 消息提醒
    flighty_notice int default 0,
    fans_notice int default 0,
    msg_notice int default 0,
    reply_notice int default 0,
    atme_notice int default 0,
    -- 配置
    msg_process enum('all', 'follow', 'none') default 'all',
    -- 索引设置
    UNIQUE (username),
    INDEX (email(10)),
    INDEX (phone(11)),
    INDEX (fans_num)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000000;";
        $this->pdo->exec($str);
    }

    protected function _getDataSet($file)
    {
        $xml_dataset = $this->createFlatXMLDataSet(dirname(__FILE__).'/_files/'.$file);
        $xml_datatable = $xml_dataset->getTable('users');
        $count = $xml_datatable->getRowCount();

        for ( $i = 0; $i < $count; $i ++ )
        {
            $value1 = md5(md5($xml_datatable->getValue($i, 'password')).md5($xml_datatable->getValue($i, 'salt')));
            $xml_datatable->setValue($i, 'password', $value1);
            $value2 = md5(md5($xml_datatable->getValue($i, 'impassword')).md5($xml_datatable->getValue($i, 'salt')));
            $xml_datatable->setValue($i, 'impassword', $value2);
        }
        return $xml_dataset;
    }

    protected function getDataSet()
    {
        return $this->_getDataSet('user_table.xml');
    }

    function testInit()
    {
        $this->mock->init(1234861);
        $this->assertEquals("帐号1234861", $this->mock->attr('username'));
        $this->mock->init('zhujiyu@abc.com');
        $this->assertEquals("zhujiyu", $this->mock->attr('username'));
    }

    function testInitBadUser()
    {
        $this->mock->init(12340);
        $this->assertEquals(0, $this->mock->ID);
    }

    function testLoadByDomain()
    {
        $this->mock->load_by_domain('zhujiyu');
        $this->assertEquals(1593490, $this->mock->ID);
        $this->mock->load_by_domain('zhujiyutest');
        $this->assertEquals(0, $this->mock->ID);
    }

    function testUpdateNotice()
    {
        $this->mock->init(1234861);
        $notices = $this->mock->update_notice();
        $this->assertEquals(12, $notices['atme_notice']);
    }

    function testNotice()
    {
        $this->mock->init(1234861);
        $this->assertEquals(12, $this->mock->attr('atme_notice'));
        $this->mock->notice('atme_notice', 10);
        $this->assertEquals(22, $this->mock->attr('atme_notice'));
        $this->assertTablesEqual($this->_getXmlTable('user_table_after_notice.xml'), $this->_getDatabaseTable());
        $this->mock->notice('atme_notice', -20);
        $this->assertEquals(2, $this->mock->attr('atme_notice'));
        $this->mock->notice('atme_notice', -20);
        $this->assertEquals(0, $this->mock->attr('atme_notice'));

        $user_table = $this->_getXmlTable('user_table_after_notice.xml');
        $user_table->setValue(0, 'atme_notice', 0);
        $this->assertTablesEqual($user_table, $this->_getDatabaseTable());
    }

    /**
     * @expectedException soParamException
     */
    function testNoticeBadParamException()
    {
        $this->mock->init(1234861);
        $this->mock->notice('follow_notice', 10);
    }

    /**
     * @expectedException soParamException
     */
    function testNoticeZeroParamException()
    {
        $this->mock->init(1234861);
        $this->mock->notice('fans_notice', 0);
    }

    /**
     * @expectedException soParamException
     */
    function testNoticeNotChangeException()
    {
        $this->mock->init(1234861);
        $this->mock->notice('fans_notice', -10);
    }

    function testCheckPassword()
    {
        $this->mock->init(1593490);
        $r = $this->mock->check_password(md5('kuke.com'));
        $this->assertTrue($r);
    }

    function testCheckIMPassword()
    {
        $this->mock->init(1593490);
        for( $i = 0; $i < 4; $i ++ )
        {
            $this->mock->check_password(md5('kuke.com'), 'imoney');
        }

        $r = $this->mock->check_password(md5('gou86.com'), 'imoney');
        $this->assertTrue($r);
        $this->mock->init(1593490, 'ID, errs');
        $this->assertEquals(0, $this->mock->attr('errs'));
        for( $i = 0; $i < 5; $i ++ )
        {
            $this->mock->check_password(md5('kuke.com'), 'imoney');
        }
    }

    /**
     * @expectedException soException
     */
    function testCheckIMPasswordException()
    {
        $this->mock->init(1593490);
        $r = $this->mock->check_password(md5('gou86.com'), 'imoney');
        $this->assertTrue($r);

        for( $i = 0; $i < 6; $i ++ )
        {
            $this->mock->check_password(md5('kuke.com'), 'imoney');
        }
    }

    function testUpdatePassword()
    {
        $this->mock->init(1593490);
        $r1 = $this->mock->update_password(md5('kuke.com'));
        $this->assertTrue(!$r1);
        $r2 = $this->mock->update_password(md5('kuuuke.com'));
        $this->assertTrue($r2);

        $user_table = $this->_getXmlTable('user_table.xml');
        $user_table->setValue(1, 'password', md5(md5('kuuuke.com').md5($user_table->getValue(1, 'salt'))));
        $this->assertTablesEqual($user_table, $this->_getDatabaseTable());
    }

    function testUpdateIMPassword()
    {
        $this->mock->init(1593490);
        $r1 = $this->mock->update_password(md5('gou86.com'), 'imoney');
        $this->assertTrue(!$r1);
        $r2 = $this->mock->update_password(md5('kuuuke.com'), 'imoney');
        $this->assertTrue($r2);

        $user_table = $this->_getXmlTable('user_table.xml');
        $user_table->setValue(1, 'impassword', md5(md5('kuuuke.com').md5($user_table->getValue(1, 'salt'))));
        $this->assertTablesEqual($user_table, $this->_getDatabaseTable());
    }

    function testInsertUser()
    {
        global $salt;
        $this->mock->insert('朱继玉', md5('332288'), md5('55332288'));
        $this->assertEquals('朱继玉', $this->mock->attr('username'));

        $user_table = $this->_getXmlTable('user_table_after_insert.xml');
        $user_table->setValue(3, 'salt', $salt);
        $user_table->setValue(3, 'password', md5(md5('332288').md5($salt)));
        $user_table->setValue(3, 'impassword', md5(md5('55332288').md5($salt)));
        $this->assertTablesEqual($user_table, $this->_getDatabaseTable());

        $this->mock->init(1593649);
        $r = $this->mock->check_password(md5('55332288'), 'imoney');
        $this->assertTrue($r);
    }

    function testFreeze()
    {
        $this->mock->init(1234861);
        $this->assertEquals(0, $this->mock->attr('freezed'));
        $this->mock->freeze(128.50);
        $this->assertEquals(128.50, $this->mock->attr('freezed'));

        $user_table = $this->_getXmlTable('user_table.xml');
        $user_table->setValue(1, 'freezed', 128.50);
        $this->assertTablesEqual($user_table, $this->_getDatabaseTable());

        $this->mock->init(1234861);
        $this->assertEquals(128.50, $this->mock->attr('freezed'));
    }

    function testUnfreeze()
    {
        $this->mock->init(1234861);
        $this->assertEquals(0, $this->mock->attr('freezed'));
        $this->mock->freeze(128.50);
        $this->mock->unfreeze(28.50);
        $this->mock->init(1234861);
        $this->assertEquals(100, $this->mock->attr('freezed'));
        $this->mock->unfreeze(100);
        $this->assertEquals(0, $this->mock->attr('freezed'));
    }

    function testUnfreezeExceptoin()
    {
        $this->mock->init(1234861);
        $this->assertEquals(0, $this->mock->attr('freezed'));
        $this->mock->freeze(128.50);

        $exceptions = 0;
        try
        {
            $this->mock->unfreeze(228.50);
        }
        catch (soParamException $ex)
        {
            $exceptions ++;
        }
        try
        {
            $this->mock->unfreeze(0);
        }
        catch (soParamException $ex)
        {
            $exceptions ++;
        }
        try
        {
            $this->mock->unfreeze(-228.50);
        }
        catch (soParamException $ex)
        {
            $exceptions ++;
        }

        if( $exceptions < 3 )
            $this->fail("解冻失败！");
    }

    function testFinance()
    {
        $this->mock->init(1234861);
        $this->assertEquals(0, $this->mock->attr('finance'));
        $this->mock->finance(128.50);
        $this->assertEquals(128.5, $this->mock->attr('finance'));
    }

    function testPay()
    {
        $this->mock->init(1234861);
        $this->assertEquals(200, $this->mock->attr('imoney'));
        $this->mock->freeze(128.50);
        $this->mock->pay(128.50);
        $this->mock->init(1234861);
        $this->assertEquals(71.50, $this->mock->attr('imoney'));
        $this->assertEquals(0, $this->mock->attr('freezed'));
    }

    /**
     * @expectedException soParamException
     */
    function testPayException()
    {
        $this->mock->init(1234861);
        $this->assertEquals(200, $this->mock->attr('imoney'));
        $this->mock->freeze(120.50);
        $this->mock->pay(128.50);
    }

    function testParam()
    {
        $this->mock->init(1234861);
        $this->assertEquals(0, $this->mock->attr('fans_num'));
        $this->mock->increase('fans_num');
        $this->assertEquals(1, $this->mock->attr('fans_num'));
    }
}
?>
