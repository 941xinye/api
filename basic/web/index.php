<?php

// comment out the following two lines when deployed to development
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('SITE_ENV') or define('SITE_ENV',get_cfg_var('site_mode'));
defined('NOW_TIME') or define('NOW_TIME', $_SERVER['REQUEST_TIME']);
defined('BASE_PATH') or define('BASE_PATH',str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/");
if(SITE_ENV==="development" || SITE_ENV==="localhost")
{
    defined('YII_DEBUG') or define('YII_DEBUG', true);
}else{
    defined('YII_DEBUG') or define('YII_DEBUG', false);
}
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
$config = require(__DIR__ . '/../config/'.strtolower(SITE_ENV).'/web.php');

(new yii\web\Application($config))->run();
