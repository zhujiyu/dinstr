#!/bin/bash

function dots
{
while true
do
  php queue.php
  echo
  sleep 5
done
}

#首先使dots函数后台运行
dots &
BG_PID=$!

sleep 30

#程序结尾注意kill dots，否则dots会一直执行
kill $BG_PID