-- 清理已经存在的公共频道
-- 公共频道的ID 设置在1000 ～ 99999 之间
delete from offices where ID > 999 and ID < 100000;
delete from office_tags where office_id > 999 and office_id < 100000;

-- InfChan开发者 开放者频道
insert into offices (ID, name, logo, domain, `type`, description)
values (1000, 'InfChan开发者', 'css/logo/infchan.kaifa.png', '1000', 'official', 'InfChan开发人员的官方频道');
insert into office_tags (office_id, tag)
values (1000, 'InfChan'), (1000, '技术'), (1000, '创业者');

-- InfChan测试者 测试人员官方频道
insert into offices (ID, name, logo, domain, `type`, description)
values (1001, 'InfChan测试者', '', '1001', 'official', 'InfChan测试人员的官方频道');
insert into office_tags (office_id, tag)
values (1001, 'InfChan'), (1001, '测试'), (1001, '创业者');

-- InfChan运营人员 运营人员官方频道
insert into offices (ID, name, logo, domain, `type`, description)
values (1002, 'InfChan运营者', '', '1002', 'official', 'InfChan运营人员的官方频道');
insert into office_tags (office_id, tag)
values (1002, 'InfChan'), (1002, '运营'), (1002, '创业者');

-- InfChan客服人员
insert into offices (ID, name, logo, domain, `type`, description)
values (1003, 'InfChan客服', '', '1003', 'official', 'InfChan客户服务人员的官方频道');
insert into office_tags (office_id, tag)
values (1003, 'InfChan'), (1003, '客服'), (1003, '创业者');

-- 创业者
insert into offices (ID, name, logo, domain, `type`, description)
values (1100, '创业者', '', '1100', 'official', '创业者频道，需要提供所从事的项目进行验证，只有实际在创业的人，才能加入本频道');
insert into office_tags (office_id, tag)
values (1100, '创业者');

-- 关注创业者
insert into offices (ID, name, logo, domain, `type`, description)
values (1101, '关注创业者', '', '1101', 'official', '关注创业，打算创业者的频道，一切热心创业的人都可以加入本频道');
insert into office_tags (office_id, tag)
values (1101, '关注创业'), (1101, '创业者');

-- 投资人
insert into offices (ID, name, logo, domain, `type`, description)
values (1102, '投资人', '', '1102', 'official', '投资人公共频道，只接受实际从事天使/VC/PE投资的人加入');
insert into office_tags (office_id, tag)
values (1102, '股权投资'), (1102, '天使投资'), (1102, '投资人');

-- 天使投资
insert into offices (ID, name, logo, domain, `type`, description)
values (1103, '天使投资', '', '1103', 'official', '天使投资人公共频道，只有专业做天使投资的人方可加入');
insert into office_tags (office_id, tag)
values (1103, '天使投资'), (1103, '投资人');
