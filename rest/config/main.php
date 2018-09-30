<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-rest',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'rest\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-rest',
        ],
        'user' => [
            'identityClass' => '\rest\models\User',
            'enableAutoLogin' => true,
            'enableSession'=> false,
            'loginUrl' => null,
            'identityCookie' => ['name' => '_identity-rest', 'httpOnly' => true],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'format' =>  \yii\web\Response::FORMAT_JSON,
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' =>  JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION,
                ],
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the rest
            'name' => 'advanced-rest',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['trace'],
                    'categories' => ['dev'],
                    'logVars' => ['_GET','_POST'],
                    'logFile' => '@app/runtime/logs/Dev/trace.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 50,
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*'request' => [
            'parsers' => [
                '*' => 'yii\web\JsonParser',
            ]
        ],*/
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                'POST,GET generate-token' => 'generate-token/index',
                'GET firesearch' => 'v0/fire-search/index',
                'POST fire-info-search' => 'v0/fire-search/fire-info',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'mya' => 'v0/my-alerts',
                    ],
                    'patterns'=>[
                        'GET,POST'       => 'index',
                        'POST get-alert'    => 'get-alert',
                        'GET check-alerts'    => 'check-alerts',
                        'GET mark-all-notification-seen'    => 'mark-all-notification-seen',
                        'GET,POST count'   => 'get-count',
                        'GET,POST alerts'   => 'get-alerts',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'info' => 'v0/info',
                    ],
                    'patterns'=>[
                        'GET sit-rep'    => 'sit-rep',
                        'POST fire-info'    => 'fire-info',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'lc' => 'v0/locations',
                        'myf' => 'v0/my-fires',
                        'fnm' => 'v0/fires-near-me',
                    ],
                    // 'except'=>['delete'],
                    // 'except' => ['update'],
                    'patterns'=>[
                        'POST delete'    => 'delete',
                        'POST create'      => 'create',
                        'GET,POST'       => 'index',
                        // 'PUT,PATCH users/<id>' => 'user/update',
                        // 'DELETE users/<id>' => 'user/delete',
                        // 'GET,HEAD users/<id>' => 'user/view',
                        // 'POST users' => 'user/create',
                        // 'GET,HEAD users' => 'user/index',
                        // 'users/<id>' => 'user/options',
                        // 'users' => 'user/options',
                    ],
                    'extraPatterns' => [
                        'POST delete'    => 'delete',
                        'POST test'      => 'test',
                    ],
                    // 'pluralize' => false,
                ],
                // [
                //     'class' => 'yii\rest\UrlRule',
                //     'controller' => ['v0/locations'],
                //     'pluralize' => false,
                //     //'controller' => ['v0/flist'],
                //     // 'only' => ['index','wfnm','alert-fires','map-fires'],
                //     /*'extraPatterns' => [
                //         'GET index'     => 'index',
                //         'GET wfnm'      => 'wfnm',
                //         'POST alert-fires'      => 'alert-fires',
                //         'POST location-based-fires'      => 'location-based-fires',
                //         'POST map-fires'      => 'map-fires',
                //         'POST wfnm-fires'      => 'wfnm-fires',
                //         'POST fire-info'      => 'fire-info',
                //         'GET sitreport'      => 'sitreport',
                //         'GET prepardness-level'      => 'prepardness-level',
                //     ],*/
                // ],
            ],
        ],
    ],
    'params' => $params,
];
