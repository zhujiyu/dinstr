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

        $this->pdo->exec("
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
        ");

        $this->pdo->exec("drop table chan_users");
        $this->pdo->exec("
CREATE TABLE chan_users
(
    ID int AUTO_INCREMENT PRIMARY KEY,
    user_id int not null,
    chan_id int not null,
    `role` int default 0, -- 0 表示订阅 1 表示成员 2 表示管理员 3 表示创建者 4 表示超级用户
--    `role` enum('subscriber', 'member', 'editor') default 'subscriber',
    weight int default 1, -- 频道的权值，这里值表示权重值的数量级
    `rank` int default 0, -- 同数量级权值的频道的排序，rank大的频道同样权值的邮件排在前面，
    opened tinyint default 1,
    join_time int default 0,
    subscribe_time timestamp,
    unique (chan_id, user_id),
    index (`role`),
    index (user_id, weight, `rank`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000
        ");

        $this->pdo->exec("
CREATE TABLE users
(
    -- 用户核心信息，其中ID，username,email都可用于登录
    ID int AUTO_INCREMENT PRIMARY KEY,
    email varchar(255), -- 邮箱注册 安全邮箱，用于找回密码，也可用于登录
    username varchar(32),
    avatar bigint default 0, -- 头像
    sign varchar(64),  -- 个性签名 一个好的个人签名，可以获得更多的信任
    -- 安全设置
    salt char(5),
    `password` char(32), -- 用md5算法将密码转成32位
    impassword char(32),  -- 资金帐号密码，支付密码
    errs tinyint default 0, -- 资金密码输入错误的次数，6次错误则锁定一小时
    last_pw_check int default 0, -- 最后一次密码检验时间，用于设置密码锁定一小时
    -- 个人基本信息
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
        ");

        $this->pdo->exec("
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

    }

    function testLoadData()
    {
//        $mock = new DisChannelData();
        $chan = new DisChannelCtrl();
        $chan->init(1234861);
        $this->assertEquals('游戏海报', $chan->attr('name'));
        $chan->init(1593490);
        $this->assertEquals('这副海报是新的 高战 ', $chan->attr('description'));
    }

    function testIncrease()
    {
        $chan = new DisChannelCtrl(1234861);
        $chan->increase("info_num");
        $this->assertEquals(42, $chan->attr('info_num'));
    }

    function testNameExist()
    {
        $r = DisChannelCtrl::name_exist("生活海报");
        $this->assertTrue($r);
    }

    function testAddMember()
    {
        $chan = new DisChannelCtrl(1234861);
        $chan->add_member(1000012);
        $this->assertEquals(1, $chan->attr('member_num'));
        $cu = new DisChanUserCtrl(1000012, 1234861);
//        $this->assertEquals(1, $cu->attr('role'));
        $this->assertEquals('member', DisChanUserCtrl::role($cu->attr('role')));
    }

    function testCreate()
    {
        $user_id = 1000000;
        $chan = DisChannelCtrl::create_new_channel($user_id, "添加海报板",
                    "business", "测试添加一个完整的海报板", 0, null);
        $this->assertEquals(0, $chan->attr('info_num'));
        $param = new DisUserParamCtrl($user_id);
        $this->assertEquals(1, $param->attr('create_num'));
    }

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

//        $this->columns = "ID, name, logo, `type`, description";
//        $this->table = "channels";
//        $this->mock = new DisChannelData();

//        $str = "
//CREATE TABLE channels
//(
//    ID int AUTO_INCREMENT PRIMARY KEY,
//    `name` varchar(32),
//    logo bigint default 0,
//    `type` enum('social', 'business', 'info', 'news') default 'social', --  社会交往social 商品交易business 商务资讯info 社会新闻news
//    description varchar(256), -- 介绍
//    announce varchar(512),    -- 公告
//    creater int, -- 创建者
//    -- 参数
//    member_num int default 0,
//    subscriber_num int default 0,
//    info_num bigint default 0,
//    applicant_num int default 0,
//    create_time int,
//    unique key (`name`),
//    index (creater),
//    index (create_time)
//)
//ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000
//";
//        $this->pdo->exec($str);

//        $this->mock->init(1234861);
//        $this->assertEquals('游戏海报', $this->mock->attr('name'));
//        $this->mock->init(1593490);
//        $this->assertEquals('这副海报是新的 高战 ', $this->mock->attr('description'));
//        $r1 = $this->mock->name_exist('测试帐号');
//        $this->assertTrue($r1);
//        $r2 = $this->mock->name_exist('新加网寨');
//        $this->assertTrue(!$r2);

//    protected function getDataSet()
//    {
//        return $this->_getDataSet('channels.xml');
//    }
?>
