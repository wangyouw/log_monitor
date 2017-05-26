#!/bin/bash
ps -ef|grep logstash|grep shipper.conf|grep -v grep|cut -c 9-15|xargs kill -9
nohup logstash-1.4.2/bin/logstash agent -f ./logstash-1.4.2/lib/logstash/config/shipper.conf &>/dev/null &
