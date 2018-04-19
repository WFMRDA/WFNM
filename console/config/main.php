<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
          ],
    ],
    'components' => [
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => Ptech_HOST,
            'hostInfo' => Ptech_HOST,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\EmailTarget',
                    'mailer' => 'mailer',
                    'levels' => ['error','warning'],
                    'message' => [
                        'from' => ['wildfiresnearme@gmail.com' => 'Cron Controller'],
                        'to' => ['Rgoolsby@firenet.gov'],
                        'subject' => 'CRON ERROR!!!!!!! Internal Error Message',
                    ],
                ]
            ],
        ],
    ],
    'params' => $params,
];
