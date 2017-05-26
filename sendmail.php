<?php  
  
ini_set("display_errors",true);
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

while(True){  
    try{  
        $maildata = $redis->brPop($config['redis_list_key']);  
        if(!empty($maildata)){
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
    $smtpObj->from($mail_form);
    $smtpObj->to($to);
    $smtpObj->subject($subject);
    $smtpObj->text($text,'text/html');
    $smtpObj->send();
}

function getTable($content){
    $table = '';
    $content = json_decode($content,true);
    foreach($content as $k=>$v){
        //过滤不想展示的字段
        if($k == '@timestamp' || $k == '@version' || $k =='type'){
            continue;
        }
        $table .="<tr><td>$k</td><td>$v</td></tr>";
    }
    $table = "<table border='1' cellspacing='0' cellpadding='10'>".$table."</table>";
    return $table;
}