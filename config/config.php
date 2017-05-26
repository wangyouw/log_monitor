<?php
    
    $redisConf = [
        'host' => 'localhost',
        'port' => 6379,
        'db' => 1,
        'timeout'=>1,
    ];
    
    $system = [
        'weixin'=>[
            'name'=>'微信',
            'monitor_log_path'=>'/tmp/logstash_all.log',//需要和logstash配置一致
            'redis_list_key'=>'maildata' ,  //邮件入队列 list key，发邮件rpop即可
            'exec_time'=>5, //接口响应阀值，单位s
            
            //邮件信息
            'mail_from'=>'wangyw3@ifeng.com',  
            'mail_to'=>'wangyw3@ifeng.com',  
            'mail_host'=>'localhost',
            'mail_port'=>25,   
            'mail_timeout'=>3, //3s
        ],
        
    ];
