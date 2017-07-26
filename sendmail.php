<?php  
/*
 * 日志监控发邮件脚本
 * date 2017/05/23
 * by wangyw3
 */

ini_set("display_errors",true);
error_reporting(E_ALL);
require_once dirname(__FILE__) . '/config/config.php';
require_once dirname(__FILE__) . '/lib/richsmtp.php';


$systemId = isset($argv[1]) ? $argv[1] : '';
if(empty($systemId) ||  !key_exists($systemId, $system)){
    echo '请按配置文件指定系统名称！';
    exit;
}
$config = $system[$systemId];

$mail_form = $config['mail_from'];
$redis = new Redis();  
$redis->pconnect($redisConf['host'],$redisConf['port']);  
//$redis->auth($redisConf['password']);

while(True){  
    try{  
        $res = $redis->brPop($config['redis_list_key'],$redisConf['timeout']);  
        if(!empty($res)){
            $maildata = $res[1];
            $maildata = json_decode($maildata,TRUE);
            $content = getTable($maildata['content']);
            mailNotice($mail_form,$maildata['consignee'], $maildata['title'], $content,$config);
            $maildata['time'] = date('Y-m-d H:i:s');
            print_r($maildata);
	}
    }catch(Exception $e){  
        echo $e->getMessage()."\n";  
    }  
//  sleep(rand()%3);  
}  


function mailNotice($mail_form,$to,$subject,$text,$config){
    $smtpObj = new richsmtp($config['mail_host'], $config['mail_port'] ,$config['mail_timeout']);
    $smtpObj->auth($config['user'], $config['pass']);
    $smtpObj->charset($config['charset']);
    $smtpObj->from($mail_form);
    $smtpObj->to($to);
    $smtpObj->subject($subject);
    $smtpObj->text($text,'text/html');
    $smtpObj->send();
    //echo $smtpObj->dump();
}

function getTable($content){
    $table = '';
    $content = json_decode($content,true);
    $fields = ['@timestamp','timestamp','@version','type','domain','request_method','agent'];
    foreach($content as $k=>$v){
        //过滤不想展示的字段
        
        if(in_array($k,$fields)){
            continue;
        }
        $table .="<tr><td>$k</td><td>$v</td></tr>";
    }
    $table = "<table border='1' cellspacing='0' cellpadding='10'>".$table."</table>";
    return $table;
}