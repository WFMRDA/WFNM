<?php
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');
/*
*Sets Base Url Before Yii App is configured.
*Used to create config variable needing Base Url
*/
if (isset($_SERVER['HTTP_HOST'])){
    define('Ptech_HOST', str_replace('admin.', '', $_SERVER['HTTP_HOST']));
}elseif (isset($_SERVER['SERVER_NAME'])){
    define('Ptech_HOST', str_replace('admin.', '', $_SERVER['SERVER_NAME']));
}
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../../backend/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../../backend/config/main.php',
    require __DIR__ . '/../../backend/config/main-local.php'
);

(new yii\web\Application($config))->run();
