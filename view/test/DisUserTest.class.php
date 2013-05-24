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
$path = dirname(__FILE__)."/../";
require_once $path."../common.inc.php";
require_once $path."user.inc.php";
//require_once dirname(__FILE__)."/../../common.inc.php";

class DisUserTest extends DisDataBaseTest
{
    function  __construct()
    {
        $sqls = array("
            drop table users
        ", "
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
            drop table user_logins
        ", "
CREATE TABLE user_logins
(
    ID int AUTO_INCREMENT PRIMARY KEY,
    user_id int,
    login int,
    logout timestamp,
    index (user_id, login)
)
ENGINE = MyISAM DEFAULT CHARSET = utf8 AUTO_INCREMENT = 100000
        ");

        $this->default_data_file = 'users.xml';
        parent::__construct($sqls);
    }

    protected function _getDataSet($file)
    {
        return parent::_getDataSet($file, dirname(__FILE__)."/res/");
    }

    function testLogin()
    {
        $r1 = _login("zhujiyu", md5('kuke.com'));
        $this->assertTrue($r1);
        $r2 = _login("zhujiyu", md5('kuuuke.com'));
        $this->assertFalse($r2);
    }

    function testLogout()
    {
        DisUserDataCache::set_last_inline(1234861);
        DisUserDataCache::set_login_id(1234861, 1000100);
        $this->assertTrue(DisUserLoginCtrl::check_inline(1234861));

        $login = new DisUserLoginCtrl(1000100);
        $login->logout();
        $this->assertFalse(DisUserLoginCtrl::check_inline(1234861));
    }

    function testSetInline()
    {
        DisUserLoginCtrl::set_inline(1593648);
        $login = new DisUserLoginCtrl(1000101);
        $this->assertEquals(date('Y-m-d H:i:s'), $login->attr('logout'));
        $this->assertEquals(time(), $login->attr('login'));
    }

    function testCheckInine()
    {
        $this->assertFalse(DisUserLoginCtrl::check_inline(1234861));

        $login = new DisUserLoginCtrl(1000100);
        $login->checkin();
        DisUserDataCache::set_last_inline($login->user_id);
        $this->assertTrue(DisUserLoginCtrl::check_inline($login->user_id));

        sleep(400);
        $this->assertFalse(DisUserLoginCtrl::check_inline($login->user_id));
    }
}
?>
