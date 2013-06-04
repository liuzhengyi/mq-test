#! /bin/sh -
# 生成damon-test4所需的vhost，user 并设定user的权限
# 一个vhost /damon4
# 3users: damon4admin, damon4writer, damon4reader
# damon4admin 可以 configure write read '^damon4.*' 的资源
# damon4writer 可以 write '^damon4.*' 的资源
# damon4reader 可以 read '^damon4.*' 的资源

sudo rabbitmqctl add_vhost '/damon4'

sudo rabbitmqctl add_user 'damon4admin' 'nimda4nomad'
sudo rabbitmqctl add_user 'damon4writer' 'retirw4nomad'
sudo rabbitmqctl add_user 'damon4reader' 'redaer4nomad'

sudo rabbitmqctl set_permissions -p /damon4 damon4admin '^damon4.*' '^damon4.*' '^damon4.*'
sudo rabbitmqctl set_permissions -p /damon4 damon4writer '^$' '^damon4.*' '^$'
sudo rabbitmqctl set_permissions -p /damon4 damon4reader '^$' '^$' '^damon4.*'
