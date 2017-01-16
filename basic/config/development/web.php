<?php
$web = require(__DIR__.'/../web.php');
$config = [
    'components' => [
        'db' => require(__DIR__ . '/db.php'),
        'db_reader' => require(__DIR__ . '/db_reader.php'),
        'redis'=>require (__DIR__.'/redis.php'),
//        'mongodb'=>require (__DIR__.'/mongo.php'),
        'wechat'=>require (__DIR__.'/wechat.php'),
//        'beanstalk' => require (__DIR__.'/beanstalk.php'),
        'upYun' => [
            'class' => 'app\backend\libs\UpYunUpload',
            'bucket'=>'qitian-uploads',
            'operator_name' => 'geminiblue',
            'operator_pwd' => 'gemini4094',
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return yii\helpers\ArrayHelper::merge($web,$config);
