<?php
/**
 * Created by PhpStorm.
 * User: gaoyuhong
 * Date: 16/4/8
 * Time: 上午9:11
 */
return [
    'class' => 'udokmeci\yii2beanstalk\Beanstalk',
    'host'=> "10.168.184.143", // default host
    'port'=>11300, //default port
    'connectTimeout'=> 1,
    'sleep' => false, // or int for usleep after every job
];

