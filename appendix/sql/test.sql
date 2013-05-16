CREATE TABLE test_notices
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