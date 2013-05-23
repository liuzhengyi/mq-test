drop table if exists `damon2Channels`;
create table `damon2Channels` (
    `channel_id` bigint unsigned auto_increment not null,
    `creator` bigint unsigned not null, -- create user id
    `listener_list` varchar(1024), -- listener user id, separate by ','. todo: use a single table for listenner_lists
    `create_time` datetime,
    `valide` boolean default true, -- 是否有效，为false说明已经失效
    primary key (`channel_id`)
) engine=MyISAM auto_increment=1 default charset=utf8;
drop table if exists `damon2Users`;
create table `damon2Users` (
    `user_id` bigint unsigned not null auto_increment,
    `token_salt` char(16) not null,
    `listen_on` char(1024) not null,
    primary key (`user_id`)
) engine=MyISAM auto_increment=1 default charset=utf8;
drop table if exists `damon2Messages`;
create table `damon2Messages` (
    `message_id` bigint unsigned not null auto_increment,
    `channel_id` bigint unsigned not null,
    `token` char(40) not null, -- hmac-sha-1 的输出长度
    `birth_time` timestamp not null,
    `deal_time` timestamp not null,
    primary key (`message_id`),
    -- unique key (`token`)
) engine=MyISAM auto_increment=1 default charset=utf8;
