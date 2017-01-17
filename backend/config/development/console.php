<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests/codeception');
$console_config = require(__DIR__.'/../console.php');
$config = [
    'components' => [
        'db' => require(__DIR__ . '/db.php'),
        'redis' => require (__DIR__.'/redis.php'),
        'mongodb'=>require (__DIR__.'/mongo.php'),
        'wechat' => require (__DIR__.'/wechat.php'),
        'beanstalk' => require (__DIR__.'/beanstalk.php'),
        'curl' => [
            'class' => 'app\backend\libs\Curl',
        ],
    ],
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];
return yii\helpers\ArrayHelper::merge($console_config,$config );
