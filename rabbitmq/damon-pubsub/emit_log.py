#!/usr/bin/env python
#coding=utf-8
import pika
import sys

connection = pika.BlockingConnection(pika.ConnectionParameters(
        host='localhost'))
channel = connection.channel()

# 声明一个叫 logs 的channel 类型为扇出
channel.exchange_declare(exchange='logs',
                         type='fanout')


message = ' '.join(sys.argv[1:]) or "info: Hello World!"

channel.basic_publish(exchange='logs',
                      routing_key='',
                      body=message)
print " [x] Sent %r" % (message,)
connection.close()
