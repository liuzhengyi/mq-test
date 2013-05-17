#!/usr/bin/env python
#coding=utf-8
import pika
import sys

connection = pika.BlockingConnection(pika.ConnectionParameters(
        host='localhost'))
channel = connection.channel()

# 声明 一个名为 direct_logs 类型为 direct 的Exchange
channel.exchange_declare(exchange='direct_logs',
                         type='direct')

# 获取命令行参数 作为 严重程度 (routing key)
severity = sys.argv[1] if len(sys.argv) > 1 else 'info'
# 构建消息体
message = ' '.join(sys.argv[2:]) or 'Hello World!'
# 发布消息到 direct_logs 交换机 routing_key 为命令行参数或默认的'info'
channel.basic_publish(exchange='direct_logs',
                      routing_key=severity,
                      body=message)

# 输出提示信息
print " [x] Sent %r:%r" % (severity, message)
connection.close()
