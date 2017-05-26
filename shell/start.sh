#!/bin/bash
nohup php /home/wwwroot/log_monitor/monitor.php weixin >> /home/wwwroot/log_monitor/monitor.log &  #监控汇总日志，写入队列
nohup php /home/wwwroot/log_monitor/sendmail.php weixin >> /home/wwwroot/log_monitor/sendmail.log & #读取队列，发送邮件

* 3 * * * cat /dev/null > /tmp/logstash_all.log  #每天定时清空日志收集文件，避免文件过大
