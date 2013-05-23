#! /bin/sh -
# 清理damon-test2所需的vhost，user 并设定user的权限
# vhost /damon2
# 3users: damon2admin, damon2writer, damon2reader

sudo rabbitmqctl delete_vhost '/damon1'

sudo rabbitmqctl delete_user 'rmq101u1'
sudo rabbitmqctl delete_user 'rmq201u1'
sudo rabbitmqctl delete_user 'rmq201u2'
sudo rabbitmqctl delete_user 'damon1admin'
