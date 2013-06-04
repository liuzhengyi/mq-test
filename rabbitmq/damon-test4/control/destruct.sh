#! /bin/sh -
# 清理damon-test4所需的vhost，user 并设定user的权限
# vhost /damon4
# 3users: damon4admin, damon4writer, damon4reader

sudo rabbitmqctl delete_vhost '/damon4'

sudo rabbitmqctl delete_user 'damon4admin'
sudo rabbitmqctl delete_user 'damon4writer'
sudo rabbitmqctl delete_user 'damon4reader'
