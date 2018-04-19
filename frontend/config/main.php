<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => '\ptech\pyrocms\models\user\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
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
        /*'view' => [
            'theme' => [
                'pathMap' => [
                    '@ptech/pyrocms/views/user' => '@frontend/views/user',
                ],
            ],
        ],*/
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
