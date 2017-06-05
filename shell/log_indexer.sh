#!/bin/bash
ps -ef|grep logstash|grep indexer.conf|grep -v grep|cut -c 9-15|xargs kill -9
nohup /root/logstash-5.4.0_index/bin/logstash  -f /root/logstash-5.4.0_index/config/indexer.conf &>/dev/null &




sudo /usr/local/logstash-5.4.0/bin/logstash -f /usr/local/logstash-5.4.0/config/indexer.conf &>/dev/null &
