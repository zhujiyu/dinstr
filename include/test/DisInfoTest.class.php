<?php
/**
 * @package: DIS.TEST
 * @file   : DisInfoTest.class.php
 * Description of DisInfoTest
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
require_once dirname(__FILE__)."/../../common.inc.php";

class DisInfoTest extends DisDataBaseTest
{
    function  __construct()
    {
        $sqls = array("
CREATE TABLE info_heads
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    content varchar(255),
    note_id bigint,
    note_num int default 0,
    interest_num int default 0,
    approved_num int default 0
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000
        ", "
CREATE TABLE info_notes
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id int not null,
    head_id bigint default 0,
    parent_id bigint default 0,
    content varchar(2560), -- 信息内容
    video varchar(255),
    -- 参数
    good_num  smallint default 0,
    photo_num smallint default 0,
    reply_num int default 0,
    status tinyint default 0, -- 0 表示草稿 1 表示发表，-1表示已经删除
    create_time int,
    index (user_id, status),
    index (head_id),
    index (parent_id)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000
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
CREATE TABLE info_users
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id int,
    head_id bigint,
    approve int default 0,
    create_time timestamp,
    index (head_id),
    index (user_id)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000
        ", "
CREATE TABLE notices
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id int not null, -- 信息的所有者
    `type` enum('reply', 'follow', 'approve', 'apply', 'invite') default 'reply',
    data_id bigint default 0,
    message varchar(255),
    create_time timestamp,
    index (user_id, `type`, data_id)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000
        ", "
CREATE TABLE latest_notices
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id int not null, -- 信息的所有者
    `type` enum('reply', 'follow', 'approve', 'apply', 'invite') default 'reply',
    data_id bigint default 0,
    message varchar(255),
    create_time timestamp,
    index (user_id, `type`, data_id)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000
        ");

        $this->default_data_file = 'infos.xml';
        parent::__construct($sqls);
    }

    function testNewInfo()
    {
        $note = DisNoteCtrl::new_info(1000000, 1234861, "信息头", "信息内容", 0);
        $this->assertEquals(1000203, $note->attr('head_id'));
        $this->assertEquals(0, $note->attr('photo_num'));

        $param = new DisUserParamCtrl(1000000);
        $this->assertEquals(1, $param->attr('note_num'));

        $chan = new DisChannelCtrl(1234861);
        $this->assertEquals(1, $chan->attr('info_num'));
    }

    function testReply()
    {
        $note = new DisNoteCtrl(1000100);
        $reply = $note->reply(1000012, "回复信息");
        $this->assertEquals(1000200, $reply->attr('head_id'));
        $this->assertEquals(0, $reply->attr('photo_num'));

        $param = new DisUserParamCtrl(1000012);
        $this->assertEquals(2, $param->attr('note_num'));
    }

    function testLoadInfos()
    {
        $user = new DisUserCtrl(1000000);
        $note_ids = $user->list_publish_note_ids(0);
        $this->assertEquals(1000101, $note_ids[0]);
        $this->assertEquals(1000100, $note_ids[1]);
    }

    function testInterest()
    {
        $head = new DisHeadCtrl(1000202);
        $user_ids1 = $head->list_interest_user_ids();
        $this->assertEquals(0, count($user_ids1));

        $head->interest(1000012);
        $user_ids2 = $head->list_interest_user_ids();
        $this->assertEquals(1, count($user_ids2));
        $this->assertEquals(1000012, $user_ids2[0]);
        $this->assertEquals(1, $head->attr('interest_num'));
    }

    function testApprove()
    {
        $head = new DisHeadCtrl(1000200);
        $user_ids1 = $head->list_approved_user_ids();
        $this->assertEquals(1, count($user_ids1));

        $head->approve(1000012);
        $user_ids2 = $head->list_approved_user_ids();
        $this->assertEquals(1, count($user_ids2));
        $this->assertEquals(1000012, $user_ids2[0]);

        $head->approve(1000000);
        $user_ids3 = $head->list_approved_user_ids();
        $this->assertEquals(2, count($user_ids3));
        $this->assertEquals(1000000, $user_ids3[0]);

        $this->assertEquals(2, $head->attr('approved_num'));
    }
}
?>
