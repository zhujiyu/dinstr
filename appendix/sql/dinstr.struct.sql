/**
 * @file： dinstr.struct.sql
 * @group: DIS
 * @brief：DIS项目的核心是提供一个为中小企业和个人发布海报的平台。
 *
 * @author： 朱继玉<zhuhz82@126.com>
 * DIS系统由原PMAIL项目简化而来。
 * Pmail以频道channel和邮件mail为中心。
 * Pmail项目为用户提供一个以频道为中心的公共信息发布和社交平台，核心
 * 理念是让重要信息主动找到用户，也就是用户一上线就能看到最重要的信息/邮件。
 *
 * @history:
 * 2012-4-11
 *    sOffice 项目正式更名为公众邮件系统，英文简写为Pmail项目。
 * 2013-4-21
 *    重组织PMAIL项目，简化成DIS。
 */
DROP DATABASE IF EXISTS dinstr;
CREATE DATABASE dinstr;
use dinstr;

/*************************************
 * 以下是基础数据模块，包括用户、频道两部分
 * 用户模块
\*************************************/
-- 用户表
-- 将用户资料，根据跟新的频率，拆分成两个表，更改频率较少的信息放在主表
DROP TABLE IF EXISTS users;
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
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000000;
select '用户表已经生成' as tip;

-- 用户信息表 存放跟新频率较高的用户参数
DROP TABLE IF EXISTS user_params;
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
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000000;
select '用户参数表已经生成' as tip;

-- 领取金币表
DROP TABLE IF EXISTS imoney_logs;
CREATE TABLE imoney_logs
(
    ID int AUTO_INCREMENT PRIMARY KEY,
    user_id int,
    imoney  int,
    log_time timestamp,
    index (user_id, log_time)
)
ENGINE = MyISAM DEFAULT CHARSET = utf8 AUTO_INCREMENT = 100000;
select '领取金币表' as tip;

-- 用户邀请表
DROP TABLE IF EXISTS user_logins;
CREATE TABLE user_logins
(
    ID int AUTO_INCREMENT PRIMARY KEY,
    user_id int,
    login int,
    logout timestamp,
    index (user_id, login)
)
ENGINE = MyISAM DEFAULT CHARSET = utf8 AUTO_INCREMENT = 100000;
select '邀请用户表已经生成' as tip;

-- 超级账户表
DROP TABLE IF EXISTS user_supers;
CREATE TABLE user_supers
(
    ID int AUTO_INCREMENT PRIMARY KEY,
    user_id int,
    index (user_id)
)
ENGINE = MyISAM DEFAULT CHARSET = utf8 AUTO_INCREMENT = 100000;
select '超级账户表已经生成' as tip;

-- 用户邀请表
DROP TABLE IF EXISTS user_invites;
CREATE TABLE user_invites
(
    ID int AUTO_INCREMENT PRIMARY KEY,
    user_id int,
    new_uid int default 0,
    email varchar(255),
    salt char(8) not null,
    code char(8) not null,
    index (code),
    index (user_id),
    index (new_uid)
)
ENGINE = MyISAM DEFAULT CHARSET = utf8 AUTO_INCREMENT = 100000;
select '邀请用户表已经生成' as tip;

-- 用户反馈表
DROP TABLE IF EXISTS user_feedbacks;
CREATE TABLE user_feedbacks
(
    ID int AUTO_INCREMENT PRIMARY KEY,
    user_id int default 0,
    `type` varchar(32),
    email varchar(255),
    content varchar(2048),
    status enum('untreated', 'doing', 'success', 'cancel') default 'untreated',
    index (user_id),
    index (email)
)
ENGINE = MyISAM DEFAULT CHARSET = utf8 AUTO_INCREMENT = 100000;
select '用户反馈表已经生成' as tip;

-- 用户关系表 用户之间的关系包括：关注，信任，互信，一体
DROP TABLE IF EXISTS user_relations;
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
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '用户关系表已经生成' as tip;

-- 黑名单
DROP TABLE IF EXISTS user_denies;
CREATE TABLE user_denies
(
    ID int AUTO_INCREMENT PRIMARY KEY,
    user_id int not null,
    denier int not null,
    INDEX (user_id, denier)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '黑名单表已经生成' as tip;

/*************************************\
 * 频道模块
\*************************************/
DROP TABLE IF EXISTS channels;
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
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '频道表已经生成' as tip;

-- 频道标签表 使用标签描述频道，使别人更好地理解该频道的主题
DROP TABLE IF EXISTS chan_tags;
CREATE TABLE chan_tags
(
    ID int AUTO_INCREMENT PRIMARY KEY,
    chan_id int,
    tag varchar(32),
    index (chan_id),
    index (tag)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '频道标签表已经生成' as tip;

-- 频道用户关系表
DROP TABLE IF EXISTS chan_users;
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
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '频道用户关系表已经生成' as tip;

-- 申请加入频道表
DROP TABLE IF EXISTS chan_applicants;
CREATE TABLE chan_applicants
(
    ID int AUTO_INCREMENT PRIMARY KEY,
    chan_id int,
    user_id int,
    reason varchar(255),
    `status` enum('untreated', 'accept', 'refuse') default 'untreated', -- 0表示没有处理，1表示申请通过，2表示申请被拒绝
    apply_time timestamp,
    index (chan_id, `status`),
    index (user_id, `status`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '申请加入频道表已经生成' as tip;

/*************************************\
 * 资源模块（商品、图片、文件夹）
\*************************************/
-- 商品/服务表
DROP TABLE IF EXISTS goods;
CREATE TABLE goods
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id int, -- 商品的签入者，第一个签入该商品的人，未必是商品的销售者
    `source` varchar(25), -- 商品/服务来源，可以是电商网站，或者是线下实体商店/服务店
    num_iid varchar(32) not null,
    title varchar(255),
    price float default 0,
    price_url varchar(255),
    pic_url varchar(255),
    click_url varchar(255),
    shop varchar(127),
    shop_url varchar(255),
    item_location varchar(255),
    -- `desc` varchar(255),
    quote int default 0,
    trade_num int default 0,
    click_num int default 0,
    update_time timestamp,
    index (`source`(10), num_iid)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '商品表已经生成' as tip;

-- 图片资源表
DROP TABLE IF EXISTS photos;
CREATE TABLE photos
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id int, -- 图片的上传者
    small varchar(255), -- 小图片地址
    big varchar(255),   -- 大图片地址
    quote int default 0,
--    url varchar(255), -- 图片地址
--    `desc` varchar(255), -- 默认图片说明
    create_time timestamp,
    index (user_id)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '图片资源表已经生成' as tip;

/*************************************\
 * 业务模块，包括私信、邮件、邮件主题
\*************************************/
-- 业务1：信息流
-- 信息头
DROP TABLE IF EXISTS info_heads;
CREATE TABLE info_heads
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    content varchar(255),
    note_id bigint,
    note_num int default 0,
    interest_num int default 0,
    approved_num int default 0,
    update_time timestamp
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '信息头表已经生成' as tip;

-- 信息体
DROP TABLE IF EXISTS info_notes;
CREATE TABLE info_notes
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id int not null,
    head_id bigint default 0,
    parent_id bigint default 0,
    content varchar(2560), -- 信息内容
    video varchar(255),
--    root_id bigint default 0,
--    context varchar(255) default "", -- 记录15条前文背景
--    `depth` int default 0, -- 深度
--    channels varchar(255),
    -- 参数
    good_num  smallint default 0,
    photo_num smallint default 0,
    reply_num int default 0,
    status tinyint default 0, -- 0 表示草稿 1 表示发表，-1表示已经删除
    create_time int,
--    publish_num int default 0, -- 发表次数
--    index (user_id, publish_num),
    index (user_id, status),
    index (head_id),
    index (parent_id)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '信息表已经生成' as tip;

-- 信息头用户关系表
DROP TABLE IF EXISTS info_users;
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
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '信息头用户关系表已经生成' as tip;

-- 信息图片表
DROP TABLE IF EXISTS info_photos;
CREATE TABLE info_photos
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    note_id bigint,
    photo_id bigint,
    `desc` varchar(255),
    `rank` tinyint, -- 表示在邮件中的显示次序
    index (note_id)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '信息图片表已经生成' as tip;

-- 信息商品表
DROP TABLE IF EXISTS info_goods;
CREATE TABLE info_goods
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    note_id bigint,
    good_id bigint,
    `desc` varchar(255),
    `rank` tinyint, -- 表示在邮件中的显示次序
    index (note_id)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '信息商品表已经生成' as tip;

-- 留言表
DROP TABLE IF EXISTS info_leaves;
CREATE TABLE info_leaves
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    note_id bigint,
    user_id int,
    index (user_id)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '留言表已经生成' as tip;

/*-- 信息关键字
DROP TABLE IF EXISTS info_keywords;
CREATE TABLE info_keywords
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    note_id bigint,
    keyword varchar(32),
    create_time timestamp,
    unique (keyword, note_id),
    index (create_time)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '信息关键字表已经生成' as tip; */

-- 业务2：用户消息表 完成用户之间的私信业务
DROP TABLE IF EXISTS messages;
CREATE TABLE messages
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    send_id int not null, -- 信息的所有者
    reciever_id int, -- 信息的发送者或者接受者
    message varchar(512),
    create_time timestamp
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '私信表已经生成' as tip;

DROP TABLE IF EXISTS message_users;
CREATE TABLE message_users
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id int not null, -- 信息的所有者
    friend_id int, -- 信息的发送者或者接受者
    message_num int default 0,
    new_message int default 0,
    last_message bigint,
    index (last_message),
    index (user_id, friend_id),
    index (message_num)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '私信统计表已经生成' as tip;

DROP TABLE IF EXISTS message_forms;
CREATE TABLE message_forms
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    relation_id bigint,
    message_id bigint,
    `read` tinyint default 0,
    index (`read`),
    index (relation_id, message_id)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '私信组织表已经生成' as tip;

-- 业务3：通知信息表
-- 系统通知
DROP TABLE IF EXISTS notices;
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
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '系统通知表已经生成' as tip;

-- 未读通知
DROP TABLE IF EXISTS new_notices;
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
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '未读通知表已经生成' as tip;

/*************************************\
 * 信息流 feed算法
\*************************************/
-- 信息流表
DROP TABLE IF EXISTS streams;
CREATE TABLE streams
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id int, -- 发布者，不一定是邮件的原作者，可能是转发者
    note_id bigint,
    chan_id int,
    weight int default 0,
    flow_time timestamp,
    index (user_id),
    index (chan_id, weight),
    index (flow_time)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '信息流表已经生成' as tip;

-- 邮件发送表
DROP TABLE IF EXISTS stream_back;
CREATE TABLE stream_back
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id int, -- 发布者，不一定是邮件的原作者，可能是转发者
    chan_id int,
    note_id bigint,
    weight int default 0,
    flow_time timestamp,
    index (user_id),
    index (chan_id, weight),
    index (flow_time)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '信息流表已经生成' as tip;

DROP TABLE IF EXISTS info_replies;
CREATE TABLE info_replies
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    note_id bigint,
    user_id int,
    index (user_id)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '回复信息表已经生成' as tip;

DROP TABLE IF EXISTS info_collects;
CREATE TABLE info_collects
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    note_id bigint,
    user_id int,
    collect_time timestamp,
    index (user_id, collect_time)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '收藏信息表已经生成' as tip;

/*
-- 用户收到信息表
DROP TABLE IF EXISTS info_feeds;
CREATE TABLE info_feeds
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id int,
    stream_id bigint,
    stream_time  int,
    index (user_id),
    index (stream_time)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '用户收到信息表已经生成' as tip;

-- 用户收到邮件表
DROP TABLE IF EXISTS note_values;
CREATE TABLE note_values
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    stream_id bigint,
    user_id int,
    weight  int,
    stream_time int,
    index (user_id, weight),
    index (stream_time)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '用户收到邮件表已经生成' as tip;

/*************************************\
 * 资金运转
\*************************************
-- 充值表
DROP TABLE IF EXISTS recharges;
CREATE TABLE recharges
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id bigint,
    money float default 0,
    pay_time timestamp,
    index (user_id, pay_time)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '付款表已经生成' as tip;

-- 消费表
DROP TABLE IF EXISTS payments;
CREATE TABLE payments
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    user_id bigint,
    mail_id bigint,
    money float default 0,
    pay_time timestamp,
    index (user_id)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '付款表已经生成' as tip;

DROP TABLE IF EXISTS note_keeps;
CREATE TABLE note_keeps
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    note_id bigint,
    user_id int,
    keep_time timestamp,
    index (user_id, mail_id)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '收藏邮件表已经生成' as tip;

-- 收藏邮件标签表
DROP TABLE IF EXISTS note_keep_tags;
CREATE TABLE note_keep_tags
(
    ID bigint AUTO_INCREMENT PRIMARY KEY,
    keep_id bigint,
    tag_id bigint,
    index (tag_id),
    index (keep_id)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000;
select '收藏邮件标签表已经生成' as tip;
*/
