<?php

exit;
/*
 * 采样上报脚本
 * date 2017/05/23
 * by wangyw3
 */
if( !(php_sapi_name() == 'cli') )
{
        exit('No direct script access allowed');
}

ini_set("display_errors",true);
require_once dirname(__FILE__) . '/config/config.php';
$systemId = isset($argv[1]) ? $argv[1] : '';
if(empty($systemId) ||  !key_exists($systemId, $system)){
    echo '请按配置文件指定系统名称！';
    exit;
}


$config = $system[$systemId];
foreach($config['monitor_url'] as $subname=>$url){
    $strat_time = time();
    curl('get',$config['domain'].$url);
    $exec_time = time()-$strat_time;
    $ret = up(json_encode(['name'=>$subname,'value'=>$exec_time]));
    echo $ret ?  '监控样本上报成功!' :'监控样本上报失败!';
}


/*
 * 上报数据
 */
function up($json){
    $up_url = 'http://127.0.0.1:1234/me/ns=pool.loda';
    $headers = array(
        "Content-type: application/json;charset='utf-8'", 
        "Accept: application/json", 
        "Cache-Control: no-cache", 
        "Pragma: no-cache", 
    );
    curl('post',$up_url,[$json],$headers);
}


function curl($curl_type,$url,$data=array(),$headers=false)
{
    $ch_curl = curl_init();
    if($curl_type == 'post'){
        
   //    curl_setopt ($ch_curl, CURLOPT_TIMEOUT, 60);
   //    curl_setopt($ch_curl, CURLOPT_SSL_VERIFYPEER, 0);
       curl_setopt($ch_curl, CURLOPT_POST, 1);
       curl_setopt ($ch_curl, CURLOPT_URL,$url);
       curl_setopt ( $ch_curl, CURLOPT_POSTFIELDS, $data );//post传输的数据。
      
    }else{
        $url = $url.'?'.http_build_query($data);
        curl_setopt($ch_curl, CURLOPT_URL, $url);
    }
    curl_setopt($ch_curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch_curl, CURLOPT_RETURNTRANSFER,true);
    $str  = curl_exec($ch_curl);
    curl_close($ch_curl);
    return $str;
}


                


