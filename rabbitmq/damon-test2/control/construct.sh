#! /bin/sh -
# 生成damon-test2所需的vhost，user 并设定user的权限
# 一个vhost /damon2
# 3users: damon2admin, damon2writer, damon2reader
# damon2admin 可以 configure write read '^damon2.*' 的资源
# damon2writer 可以 write '^damon2.*' 的资源
# damon2reader 可以 read '^damon2.*' 的资源

sudo rabbitmqctl add_vhost '/damon2'

sudo rabbitmqctl add_user 'damon2admin' 'nimda2nomad'
sudo rabbitmqctl add_user 'damon2writer' 'retirw2nomad'
sudo rabbitmqctl add_user 'damon2reader' 'redaer2nomad'

sudo rabbitmqctl set_permissions -p /damon2 damon2admin '^damon2.*' '^damon2.*' '^damon2.*'
sudo rabbitmqctl set_permissions -p /damon2 damon2writer '^$' '^damon2.*' '^$'
sudo rabbitmqctl set_permissions -p /damon2 damon2reader '^$' '^$' '^damon2.*'
