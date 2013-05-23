#! /bin/sh -
# 生成damon-test1所需的vhost，user 并设定user的权限
# 1 vhost: /damon1
# 4 users: rmq101u1, rmq201u1, rmq201u2, damon1admin
# user privileges:
# (u1)rmq101u1: read '^rmq101.*' , write '^rmq101.*'
# (u2)rmq201u1: read '^rmq[12]01.*' , write '^rmq201.*'
# (u3)rmq201u2: read '^rmq201' , write '^rmq[12]01'
# (u4)damon1admin: config '.*'
# 

sudo rabbitmqctl add_vhost '/damon1'

sudo rabbitmqctl add_user 'rmq101u1' '1u101qmr'
sudo rabbitmqctl add_user 'rmq201u1' '1u102qmr'
sudo rabbitmqctl add_user 'rmq201u2' '2u102qmr'
sudo rabbitmqctl add_user 'damon1admin' 'nimda1nomad'

sudo rabbitmqctl set_permissions -p /damon1 rmq101u1 '^$' '^rmq101.*' '^rmq101.*'
sudo rabbitmqctl set_permissions -p /damon1 rmq201u1 '^$' '^rmq[12]01.*' '^rmq201.*'
sudo rabbitmqctl set_permissions -p /damon1 rmq201u2 '^$' '^rmq201.*' '^rmq[12]01.*'
sudo rabbitmqctl set_permissions -p /damon1 damon1admin '.*' '^$' '^$'
