<?php
$a = var_export(['sd'=>11]);
echo $a;exit; 
preg_match('/.*[error].*/', '1[err1or]tail: /tmp/logstash_all.log：文件已截断',$match);
var_dump($match);
exit;
require_once dirname(__FILE__) . '/config/config.php';

$system = isset($argv[1]) ? $argv[1] : '';
if(!empty($system) ||  !in_array($system, $system)){
    echo '请按配置文件指定系统名称！';
    exit;
}
//if(!isset($argv[2]) ||  in_array($argv[2], ['mailCron','monitor'])){
//    echo '请指定模块！';
//    exit;
//}

exec("nohup php /home/wwwroot/log_monitor/mailCron.php $system>> /home/wwwroot/log_monitor/mailCron.log &");
exec("nohup php /home/wwwroot/log_monitor/monitor.php $system >> /home/wwwroot/log_monitor/monitor.log &");

