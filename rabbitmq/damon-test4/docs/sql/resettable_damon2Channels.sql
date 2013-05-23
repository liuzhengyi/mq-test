drop table if exists `damon2Channels`;
create table `damon2Channels` (
    `channel_id` bigint unsigned auto_increment not null,
    `creator` bigint unsigned not null, -- create user id
    `listenner_list` varchar(1024), -- listenner user id, separate by ','. todo: use a single table for listenner_lists
    `create_time` datetime,
    `valide` boolean default true, -- 是否有效，为false说明已经失效
    primary key (`channel_id`)
) engine=MyISAM auto_increment=1 default charset=utf8;
