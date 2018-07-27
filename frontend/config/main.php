<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $protocol = 'https://';
}
else {
  $protocol = 'http://';
}

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules'=>[
        'pyrocms' => [
            'class' => 'ptech\pyrocms\PyroCms',
            'user'=> [
                'class' => 'ptech\pyrocms\User',
                'controllerMap' => [
                    'settings' => 'frontend\controllers\SettingsController',
                ],
            ]
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => '\common\models\user\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'response' => [
           'class' => 'yii\web\Response',
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                     'class' => 'yii\web\JsonResponseFormatter',
                     'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                     'encodeOptions' =>  JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION,
                ],
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl' => $protocol. Ptech_HOST,
            'rules' => [
            ],
        ],
        'urlManagerBackEnd' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'http://'. str_replace('www.', '', Ptech_HOST).'/admin',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => '@frontend/assets/dist',
                    'css'=>[
                        YII_ENV_DEV ? 'css/bootstrap.css' : '//assets.wildfiresnearme.wfmrda.com/css/bootstrap.css'
                    ],
                ],
            ],
        ],
        'appSystemData' => [
            'class' => 'frontend\components\SystemData',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@ptech/pyrocms/views/user' => '@frontend/views/user',
                ],
            ],
        ],
    ],
    /*'as access' => [
        'class' => \yii\filters\AccessControl::className(),//AccessControl::className(),
        'rules' => [
           [
               'actions' => ['count','login', 'error','request-password-reset','resend-confirmation','update-password','confirm','signup','captcha','process-updates'],
               'allow' => true,
           ],
           [

               'allow' => true,
               'roles' => ['@'],
           ],
        ],
    ],*/
    'params' => $params,
];
