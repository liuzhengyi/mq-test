drop table if exists `damon2Users`;
create table `damon2Users` (
    `user_id` bigint unsigned not null auto_increment,
    `token_salt` char(16) not null,
    `listen_on` char(1024) not null,
    primary key (`user_id`)
) engine=MyISAM auto_increment=1 default charset=utf8;
