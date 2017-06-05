#!/bin/bash
ps -ef|grep logstash|grep shipper.conf|grep -v grep|cut -c 9-15|xargs kill -9
nohup /root/logstash-5.4.0/bin/logstash  -f /root/logstash-5.4.0/config/shipper.conf &>/dev/null &



sudo /usr/local/logstash-5.4.0/bin/logstash -f /usr/local/logstash-5.4.0/config/shipper.conf &>/dev/null &