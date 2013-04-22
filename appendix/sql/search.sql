DROP TABLE IF EXISTS soffice.searchs;

CREATE TABLE soffice.searchs
(
	id int PRIMARY KEY AUTO_INCREMENT,
	search_index varchar(32) default 'wishes',
	max_id int default 0,
	date_added timestamp,
    index (search_index)
);

insert into soffice.searchs (search_index, max_id)
values ('_news', 0), ('_office', 0), ('_wishes', 0);
