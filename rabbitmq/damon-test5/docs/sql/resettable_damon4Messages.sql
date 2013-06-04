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
