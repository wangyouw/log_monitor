<?php
/*
 * 日志监控脚本
 * date 2017/05/23
 * by wangyw3
 */

ini_set("display_errors",true);
require_once dirname(__FILE__) . '/config/config.php';

$systemId = isset($argv[1]) ? $argv[1] : '';
if(empty($systemId) ||  !key_exists($systemId, $system)){
    echo '请按配置文件指定系统名称！';
    exit;
}
$config = $system[$systemId];
$exec_time=$config['exec_time'];
$consignee=$config['mail_to'];
$log_path=$config['monitor_log_path'];
$systemName = $config['name'];
$handle = popen("tail -n 0 -f $log_path 2>&1", 'r');

$redis = new redis();
$redis->pconnect($redisConf['host'],$redisConf['port']);  
//$redis->auth($redisConf['password']);

while(!feof($handle)) {
    try{
    
        $flag = false;
        $buffer = fgets($handle);
        echo "$buffer\n";
        if(preg_match('/.*\[ExecTime:(.*?)s\].*(app_err).*/', $buffer,$match) && $match[1] > $exec_time){
            $flag = true;
            $title="{$systemName}系统-应用错误告警";
        }elseif(preg_match('/.*PHP Fatal error|PHP Parse error.*(php_err).*/', $buffer)){
            $flag = true;
            $title="{$systemName}系统-接口异常";
        }elseif(preg_match('/.*\\\"(\d+\.\d+)\\\".*(logstash_nginx_log).*/', $buffer,$logMah) && $logMah[1] > $exec_time){
            $flag = true;
            $title="{$systemName}系统-nginx request_time 响应时间过长";
        }elseif(preg_match('/.*\[error\].*/', $buffer)){
            $flag = true;
            $title="{$systemName}系统-PHP_ERR";
        }

        if($flag){
            $data = [
                'consignee'=>$consignee,
                'title'=>$title,
                'content'=>$buffer,
            ];
            $redis->lpush($config['redis_list_key'],json_encode($data));
        }
        flush();
    }  catch (Exception $e){
        echo $e->getMessage()."\n";  
    }
}
$redis->close($handle);