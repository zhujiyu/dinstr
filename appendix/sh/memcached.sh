#!/bin/bash
# 启动
/usr/bin/memcached -d -m 32 -u jiyu -l localhost -p 11211 -c 256 -P /tmp/memcached.pid
# 结束
# kill `cat /tmp/memcached.pid`
# 或者：
# ps -aux | grep memcache
# 然后直接kill掉memcache进程。
