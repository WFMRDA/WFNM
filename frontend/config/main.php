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
    'modules' => [
        'redactor' => [
            'class' => 'yii\redactor\RedactorModule',
            'uploadDir' => '@frontend/web/d/u/bio',
            'uploadUrl' => 'http://'.Ptech_HOST.'/d/u/bio',
        ],
        'modules' => [
            'user' => [
                // following line will restrict access to admin controller from frontend application
                'as frontend' => 'common\modules\User\filters\FrontendFilter',
                'controllerMap' => [
                    // 'settings' => 'frontend\controllers\SettingsController',
                      // 'security' => 'frontend\controllers\SecurityController'
                ],
            ],
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\user\User',
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
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@common/modules/User/views' => '@frontend/views/user'
                ],
            ],
        ],
    ],
    'as access' => [
        'class' => \yii\filters\AccessControl::className(),//AccessControl::className(),
        'rules' => [
           [
               'actions' => ['login', 'error','request-password-reset','resend-confirmation','update-password','confirm','signup','captcha'],
               'allow' => true,
           ],
           [

               'allow' => true,
               'roles' => ['@'],
           ],
        ],
    ],
    'params' => $params,
];
