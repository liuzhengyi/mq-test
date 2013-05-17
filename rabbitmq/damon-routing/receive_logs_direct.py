#!/usr/bin/env python
#coding=utf-8
import pika
import sys

connection = pika.BlockingConnection(pika.ConnectionParameters(
        host='localhost'))
# jjj创建一个channel
channel = connection.channel()

# 声明一个交换机 名称为 direct_logs 类定为 direct
channel.exchange_declare(exchange='direct_logs',
                         type='direct')

# 声明一个随机名称的临时队列
result = channel.queue_declare(exclusive=True)
# 获取queue的名字
queue_name = result.method.queue

severities = sys.argv[1:]
if not severities:
    print >> sys.stderr, "Usage: %s [info] [warning] [error]" % \
                         (sys.argv[0],)
    sys.exit(1)

# 绑定命令行参数指定的routingkey
for severity in severities:
    channel.queue_bind(exchange='direct_logs',
                       queue=queue_name,
                       routing_key=severity)

print ' [*] Waiting for logs. To exit press CTRL+C'

def callback(ch, method, properties, body):
    print " [x] %r:%r" % (method.routing_key, body,)

channel.basic_consume(callback,
                      queue=queue_name,
                      no_ack=True)

channel.start_consuming()
