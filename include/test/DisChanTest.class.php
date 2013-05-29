<?php
/**
 * @package: DIS.TEST
 * @file   : DisChanTest.class.php
 * Description of DisChanTest
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
require_once dirname(__FILE__)."/../../common.inc.php";

class DisChanTest extends DisDataBaseTest
{
    function  __construct()
    {
        $sqls = array("drop table channels", "
CREATE TABLE channels
(
    ID int AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(32),
    `type` enum('info', 'business', 'social') default 'info',
    -- 商务资讯info
    -- 商品交易business
    --  社会交往social
    logo bigint default 0,
    `desc` varchar(256), -- 介绍
    bulletin varchar(512),    -- 公告
    creater int, -- 创建者
    opened tinyint default 1,
    -- 参数
    member_num int default 0,
    subscriber_num int default 0,
    applicant_num int default 0,
    info_num bigint default 0,
    create_time int,
    unique key (`name`),
    index (creater),
    index (create_time)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000
        ", "
CREATE TABLE chan_users
(
    ID int AUTO_INCREMENT PRIMARY KEY,
    user_id int not null,
    chan_id int not null,
    `role` int default 0, -- 0 表示订阅 1 表示成员 2 表示管理员 3 表示创建者 4 表示超级用户
    weight int default 1, -- 频道的权值，这里值表示权重值的数量级
    `rank` int default 0, -- 同数量级权值的频道的排序，rank大的频道同样权值的邮件排在前面，
    join_time int default 0,
    subscribe_time timestamp,
    unique (chan_id, user_id),
    index (`role`),
    index (user_id, weight, `rank`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000
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
    last_check int default 0, -- 最后一次密码检验时间，用于设置密码锁定一小时
    check_errs tinyint default 0, -- 资金密码输入错误的次数，6次错误则锁定一小时
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
        ", "drop table chan_applicants", "
CREATE TABLE chan_applicants
(
    ID int AUTO_INCREMENT PRIMARY KEY,
    chan_id int,
    user_id int,
    reason varchar(255),
    `status` enum('untreated', 'accepted', 'refused') default 'untreated', -- 0表示没有处理，1表示申请通过，2表示申请被拒绝
    apply_time timestamp,
    index (chan_id, `status`),
    index (user_id, `status`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000
        ", "
CREATE TABLE latest_notices
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id int not null, -- 信息的所有者
--    `type` enum('mail', 'approve', 'reply', 'apply', 'fan', 'invite') default 'mail', --
    `type` enum('reply', 'follow', 'approve', 'apply', 'invite') default 'reply',
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
    `type` enum('reply', 'follow', 'approve', 'apply', 'invite') default 'reply',
    -- reply 发出的信息或者评论收到回复
    -- follow 有人关注
    -- invite 受到加入某个频道的邀请
    -- approve 发出的信息收到赞同
    -- apply 加入频道的申请通知
    data_id bigint default 0,
    message varchar(255),
    create_time timestamp,
    index (user_id, `type`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000
        ");

        $this->default_data_file = "channels.xml";
        parent::__construct($sqls);
    }

    function testLoadData()
    {
        $chan = new DisChannelCtrl(1234861);
        $this->assertEquals('游戏海报', $chan->attr('name'));
        $chan->init(1593490);
        $this->assertEquals('这副海报是新的 高战 ', $chan->attr('desc'));
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

    function testAddSubscriber()
    {
        $chan = new DisChannelCtrl(1593648);
        $chan->add_subscriber(1000012);
        $this->assertEquals(2, $chan->attr('subscriber_num'));
        $cu = new DisChanUserCtrl(1000012, 1593648);
        $this->assertEquals('subscriber', DisChanUserCtrl::role($cu->attr('role')));
        $param = new DisUserParamCtrl(1000012);
        $this->assertEquals(1, $param->attr('subscribe_num'));
    }

    /**
     * @expectedException DisParamException
     */
    function testRemoveSubscriber()
    {
        $chan = new DisChannelCtrl(1593648);
        $this->assertEquals(1, $chan->attr('subscriber_num'));
        $chan->remove_subscriber(1000000);
        $this->assertEquals(0, $chan->attr('subscriber_num'));
        $param = new DisUserParamCtrl(1000000);
        $this->assertEquals(2, $param->attr('subscribe_num'));
        $cu2 = new DisChanUserCtrl(1000000, 1593648);
        $this->assertEquals('subscriber', DisChanUserCtrl::role($cu2->attr('role')));
    }

    function testAddMember()
    {
        $chan1 = new DisChannelCtrl(1593648);
        $chan1->add_member(1000012);
        $this->assertEquals(2, $chan1->attr('subscriber_num'));
        $this->assertEquals(1, $chan1->attr('member_num'));
        $cu1 = new DisChanUserCtrl(1000012, 1593648);
        $this->assertEquals('member', DisChanUserCtrl::role($cu1->attr('role')));

        $chan2 = new DisChannelCtrl(1593648);
        $cu2 = new DisChanUserCtrl(1000000, 1593648);
        $this->assertEquals('subscriber', DisChanUserCtrl::role($cu2->attr('role')));
        $chan2->add_member(1000000);
        $this->assertEquals(2, $chan2->attr('subscriber_num'));
        $this->assertEquals(2, $chan2->attr('member_num'));
        $cu3 = new DisChanUserCtrl(1000000, 1593648);
        $this->assertEquals('member', DisChanUserCtrl::role($cu3->attr('role')));

        $param = new DisUserParamCtrl(1000012);
        $this->assertEquals(1, $param->attr('join_num'));
    }

    function testRemoveMember()
    {
        $chan1 = new DisChannelCtrl(1593648);
        $chan1->remove_member(1000000);
        $cu1 = new DisChanUserCtrl(1000000, 1593648);
        $this->assertEquals('subscriber', DisChanUserCtrl::role($cu1->attr('role')));

        $param1 = new DisUserParamCtrl(1000000);
        $this->assertEquals(2, $param1->attr('join_num'));

        $chan2 = new DisChannelCtrl(1593490);
        $chan2->remove_member(1000000);
        $cu2 = new DisChanUserCtrl(1000000, 1593490);
        $this->assertEquals('subscriber', DisChanUserCtrl::role($cu2->attr('role')));

        $param2 = new DisUserParamCtrl(1000000);
        $this->assertEquals(1, $param2->attr('join_num'));
    }

    function testCreate()
    {
        $user_id = 1000000;
        $chan = DisChannelCtrl::create_channel($user_id, "添加海报板",
                    "business", "测试添加一个完整的海报板", 0, null);
        $this->assertEquals(0, $chan->attr('info_num'));
        $param = new DisUserParamCtrl($user_id);
        $this->assertEquals(1, $param->attr('create_num'));
    }

    function testApply()
    {
        $chan1 = new DisChannelCtrl(1593490);
        $chan1->apply(1000012, "申请加入");
        $this->assertEquals(1, $chan1->attr('applicant_num'));

        $user = new DisUserParamCtrl(1000012);
        $this->assertEquals(2, $user->attr('applicant_num'));

        $row = $this->_getDBRow('chan_applicants', "ID, chan_id, user_id, reason, status, apply_time",
                "ID = 1000012");
        $time = $row['apply_time'];

        $t1 = $this->_getXmlTable('chan_applicants', 'channels_after_apply.xml');
        $t1->setValue(2, 'apply_time', $time);
        $t2 = $this->_getDatabaseTable('chan_applicants', "ID, chan_id, user_id, reason, status, apply_time");
        $this->assertTablesEqual($t1, $t2);
    }

    function testAcceptApply()
    {
        $chan1 = new DisChannelCtrl(1593648);
        $chan1->accept_apply(1000000);
        $this->assertEquals(1, $chan1->attr('applicant_num'));
        $this->assertEquals(1, $chan1->attr('member_num'));
        $this->assertEquals(2, $chan1->attr('subscriber_num'));

        $user = new DisUserParamCtrl(1000012);
        $this->assertEquals(0, $user->attr('applicant_num'));
        $this->assertEquals(1, $user->attr('subscribe_num'));
        $this->assertEquals(1, $user->attr('join_num'));

        $apply = new DisChanApplicantCtrl(1000000);
        $this->assertEquals('accepted', $apply->attr('status'));

        $chan1->accept_apply(1000011);
        $this->assertEquals(0, $chan1->attr('applicant_num'));
        $this->assertEquals(2, $chan1->attr('member_num'));
        $this->assertEquals(2, $chan1->attr('subscriber_num'));

        $user2 = new DisUserParamCtrl(1000000);
        $this->assertEquals(0, $user2->attr('applicant_num'));
        $this->assertEquals(3, $user2->attr('join_num'));
        $this->assertEquals(3, $user2->attr('subscribe_num'));

        $nctrl = new DisNoticeCtrl(1000000);
        $notice_ids = $nctrl->get_unread_notice_ids();
        $notice = DisNoticeCtrl::load_notice($notice_ids[0]);
        $this->assertEquals(1000011, $notice['data_id']);
    }

    /**
     * @expectedException DisParamException 无效的操作，该频道不存在此条申请！
     */
    function testRefuseApply()
    {
        $chan1 = new DisChannelCtrl(1593648);
        $chan1->refuse_apply(1000000);
        $this->assertEquals(1, $chan1->attr('applicant_num'));

        $user = new DisUserParamCtrl(1000012);
        $this->assertEquals(0, $user->attr('applicant_num'));

        $apply = new DisChanApplicantCtrl(1000000);
        $this->assertEquals('refused', $apply->attr('status'));

        $nctrl = new DisNoticeCtrl(1000012);
        $notice_ids = $nctrl->get_unread_notice_ids();
        $notice = DisNoticeCtrl::load_notice($notice_ids[0]);
        $this->assertEquals(1000000, $notice['data_id']);

        $chan3 = new DisChannelCtrl(1234861);
        $chan3->refuse_apply(1000000);
    }
}

//$file = "common.inc.php";
//for( $i = 0; $i < 5; $i ++ )
//{
//    if( file_exists($file) )
//    {
//        require_once ( $file );
//        break;
//    }
//    $file = "../$file";
//}

//        $this->columns = "ID, name, logo, `type`, description";
//        $this->table = "channels";
//        $this->mock = new DisChannelData();

?>
