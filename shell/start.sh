#!/bin/bash

#-----dev-----
nohup php /home/wwwroot/log_monitor/monitor.php erweima >> /home/wwwroot/log_monitor/log/monitor.log &  #监控汇总日志，写入队列
nohup php /home/wwwroot/log_monitor/sendmail.php erweima >> /home/wwwroot/log_monitor/log/sendmail.log & #读取队列，发送邮件


#-----online----
#监控汇总日志，写入队列
sudo nohup /usr/local/php/bin/php /data/ifengsite/htdocs/log_monitor/monitor.php erweima >> /data/logs/logstash/monitor.log &
#读取队列，发送邮件
sudo nohup /usr/local/php/bin/php /data/ifengsite/htdocs/log_monitor/sendmail.php erweima >> /data/logs/logstash/sendmail.log &


* 3 * * * cat /dev/null > /data/logs/logstash/logstash_all.log  #每天定时清空日志收集文件，避免文件过大
