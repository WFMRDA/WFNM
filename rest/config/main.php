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
    	'jwt' => [
    		'class' => 'rest\models\jwt\Jwt',
            'passphrase' => '',
            'uniqueToken' => 'auth_key',// – Column from User Table will be used as a unique string, could be used to validate a token, but goes against not having a centralized issuer authority.
            'serverName' => Ptech_HOST,// – A string containing the name or identifier of the issuer application. Can be a domain name and can be used to discard tokens from other applications.
            'exp' => 315360000,// 10 years
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                'POST,GET generate-token' => 'generate-token/index',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v0/locations'],
                    'pluralize' => false,
                    //'controller' => ['v0/flist'],
                    // 'only' => ['index','wfnm','alert-fires','map-fires'],
                    /*'extraPatterns' => [
                        'GET index'     => 'index',
                        'GET wfnm'      => 'wfnm',
                        'POST alert-fires'      => 'alert-fires',
                        'POST location-based-fires'      => 'location-based-fires',
                        'POST map-fires'      => 'map-fires',
                        'POST wfnm-fires'      => 'wfnm-fires',
                        'POST fire-info'      => 'fire-info',
                        'GET sitreport'      => 'sitreport',
                        'GET prepardness-level'      => 'prepardness-level',
                    ],*/
                ],
            ],
        ],
    ],
    'params' => $params,
];
