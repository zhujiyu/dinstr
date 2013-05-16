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
        $sqls = array("
CREATE TABLE users
(
    -- 用户核心信息，其中ID，username,email都可用于登录
    ID int AUTO_INCREMENT PRIMARY KEY,
    email varchar(255), -- 邮箱注册 安全邮箱，用于找回密码，也可用于登录
    username varchar(32),
    avatar bigint default 0, -- 头像
    -- 安全设置
    salt char(32),
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
        ", "
CREATE TABLE user_relations
(
    ID int AUTO_INCREMENT PRIMARY KEY,
    `from_user` int,
    `to_user` int,
    `read` tinyint default 0,
    follow_time timestamp,
    unique (`from_user`, `to_user`),
    index (`to_user`),
    index (`follow_time`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000
        ", "
CREATE TABLE new_notices
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id int not null, -- 信息的所有者
    `type` enum('mail', 'approve', 'reply', 'apply', 'fan', 'invite') default 'mail', --
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
    `type` enum('mail', 'approve', 'reply', 'apply', 'fan', 'invite') default 'mail', --
    data_id bigint default 0,
    message varchar(255),
    create_time timestamp,
    index (user_id, `type`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000
        ");

        parent::__construct($sqls);
        $this->default_data_file = 'users.xml';
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

        $r1 = $user->check_password(md5('heibaoban'), 'imoney');
        $this->assertTrue($r1);
        $user->init(1593490, "ID, username, sign, live_city, contact, self_intro, check_errs");
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

    function testUpdatePassword()
    {
        $user = new DisUserCtrl(1593490);
        $r1 = $user->update_password(md5('kuke.com'));
        $this->assertTrue($r1);
        $r2 = $user->update_password(md5('heibaoban.com'));
        $this->assertTrue($r2);
        $user_t1 = $this->_getDatabaseTable('users',
                'ID, username, email, salt, password, impassword');

        $user_t2 = $this->_getXmlTable('users');
        $salt = $user_t2->getValue(1, 'salt');
        $user_t2->setValue(1, 'password', md5(md5('heibaoban.com').$salt));
        $this->assertTablesEqual($user_t1, $user_t2);
    }

    function testUpdateIMPassword()
    {
        $user = new DisUserCtrl(1593490);
        $r1 = $user->update_password(md5('gou86.com'), 'imoney');
        $this->assertTrue($r1);
        $r2 = $user->update_password(md5('heibaoban.com'), 'imoney');
        $this->assertTrue($r2);
        $user_t1 = $this->_getDatabaseTable('users',
                'ID, username, email, salt, password, impassword');

        $user_t2 = $this->_getXmlTable('users');
        $salt = $user_t2->getValue(1, 'salt');
        $user_t2->setValue(1, 'impassword', md5(md5('heibaoban.com').$salt));
        $this->assertTablesEqual($user_t1, $user_t2);
    }

    function testFollow()
    {
        $user = new DisUserCtrl(1593490);
        $user->follow(1234861);

        $p1 = new DisUserParamCtrl(1593490);
        $this->assertEquals(2, $p1->attr('follow_num'));
        $p2 = new DisUserParamCtrl(1234861);
        $this->assertEquals(11, $p2->attr('fans_num'));

        $row = $this->_getDBRow('user_relations', "ID, `from_user`, `to_user`, `read`, follow_time",
                "ID = 1000035");
        $time = $row['follow_time'];
        $t1 = $this->_getXmlTable('user_relations', 'users_after_insert.xml');
        $t1->setValue(4, 'follow_time', $time);
        $t2 = $this->_getDatabaseTable('user_relations', "ID, `from_user`, `to_user`, `read`, follow_time");
        $this->assertTablesEqual($t1, $t2);
    }

    function testCancelFollow()
    {
        $user = new DisUserCtrl(1234861);
        $user->cancel_follow(1593490);

        $p1 = new DisUserParamCtrl(1234861);
        $this->assertEquals(1, $p1->attr('follow_num'));
        $p2 = new DisUserParamCtrl(1593490);
        $this->assertEquals(0, $p2->attr('fans_num'));

        $t2 = $this->_getXmlTable('user_relations', 'users_after_cf.xml');
        $t1 = $this->_getDatabaseTable('user_relations', "ID, `from_user`, `to_user`, `read`, follow_time");
        $this->assertTablesEqual($t1, $t2);
    }

    function testRegister()
    {
        $user = DisUserCtrl::register('朱继玉', md5('332288'), 'zhuhz82@126.com');
        $this->assertEquals('朱继玉', $user->attr('username'));
        $user_t1 = $this->_getDatabaseTable('users', 'ID, username, email, salt, password, impassword');

        $row = $this->_getDBRow('users', 'ID, username, email, salt, password, impassword',
                "username = '朱继玉'");
        $salt = $row['salt'];

        $user_t2 = $this->_getXmlTable('users', 'users_after_insert.xml');
        $user_t2->setValue(3, 'salt', $salt);
        $user_t2->setValue(3, 'password', md5(md5('332288').$salt));
//        $user_t2->setValue(3, 'impassword', md5(md5('55332288').$salt));
        $this->assertTablesEqual($user_t2, $user_t1);
    }

    function testParam()
    {
        $user = new DisUserParamCtrl(1234861);
        $this->assertEquals(10, $user->attr('fans_num'));
        $user->increase('fans_num');
        $this->assertEquals(11, $user->attr('fans_num'));
        $user->increase('fans_num', 3);
        $this->assertEquals(14, $user->attr('fans_num'));
        $user->reduce('fans_num', 10);
        $this->assertEquals(4, $user->attr('fans_num'));
        $user->reduce('fans_num', 10);
        $this->assertEquals(0, $user->attr('fans_num'));
    }

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
