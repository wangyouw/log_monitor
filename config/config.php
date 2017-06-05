<?php
    
    $redisConf = [
        'host' => 'localhost',
        'port' => 6379,
//        'password' => "lL1fCBq4crR3XZQ28tJQ",
        'db' => 1,
        'timeout'=>5,
    ];
    
    $system = [
        'erweima'=>[
            'name'=>'二维码',
            'domain'=>'http://qrcode.ifeng.com',
            'monitor_log_path'=>'/tmp/logstash_all.log',//需要和logstash配置一致
            'redis_list_key'=>'maildata' ,  //邮件入队列 list key，发邮件rpop即可
            'exec_time'=>5, //接口响应阀值，单位s(这里和logstash【它会先过滤一次】需要同步修改）
            
            'monitor_url'=>[
                'subname1'=>'/qrcode.php?callback=jQuery171034365962026640773_1495873702779&url=http%3A%2F%2Fv.ifeng.com%2Fdyn%2Fm%2Fvideo%2F7380644%2Findex.shtml%3F_share%3Dweixin',
                'subname2'=>'/2017/05/27/56163f5e5c0c7d1d16f8ace3c0a2d694.png',
            ],
            
            //邮件信息
            'mail_from'=>'wangyw3@ifeng.com',  
            'mail_to'=>'wangyw3@ifeng.com',  
            //'mail_host'=>'localhost',
            'mail_host'=>'mail.ifeng.com',
            'user'=>'wangyw3',
            'auth'=>true,
            'pass'=>'siyu@YOUWEN1015',
	    'charset'=> 'utf-8',
            'mail_port'=>25,   
            'mail_timeout'=>3, //3s
        ],
        
    ];
