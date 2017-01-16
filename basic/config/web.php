<?php

$params = require(__DIR__ . '/params.php');
error_reporting(E_ERROR);
$config = [
    'id' => '941xinye',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'api/login/index',
    'modules' => [
        'api'=>[
            'class'=>'app\modules\ful\Module'
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'BKNSxD-1-Amlw2f-pe5w32nxTZd-nFtt',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->data !== null&&$response->isSuccessful==false) {
                    $response->data = [
                        'errcode' => $response->data['status'],
                        'errmsg'=> $response->data['message'],
                        'data' => $response->data,
                    ];
                }
            },
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Members',
            'enableAutoLogin' => true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/version',
                    'extraPatterns' => [
                        'GET versions' => 'version',
                        'GET androids' =>'android',
                    ],
                ],
                ['class' =>
                    'yii\rest\UrlRule',
                    'controller' => 'api/login',
                    'extraPatterns' => [
                        'GET logins' => 'login',
                    ],
                ],
                ['class' =>
                    'yii\rest\UrlRule',
                    'controller' => 'api/user',
                    'extraPatterns' => [
                        'GET infos' => 'info',
                    ],
                ],
            ],
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logVars' => [],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['log.test'],
                    'logFile' => '@app/runtime/logs/log/test.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                ],
            ],
        ],
//        'upYun' => [
//            'class' => 'app\backend\libs\UpYunUpload',
//            'bucket' => 'qitian-uploads',
//            'operator_name' => 'geminiblue',
//            'operator_pwd' => 'gemini4094',
//        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            // 'db' => 'mydb',  // 数据库连接的应用组件ID，默认为'db'.
            'sessionTable' => 'pre_session', // session 数据表名，默认为'session'.
        ],
    ],
    'params' => $params,
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

return $config;
