<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'name'=> 'Wildfires Near Me',
    'components' => [
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => '6LfHClQUAAAAANa89IJS4DhqWhruXEXpGd_uW_-V',
            'secret' => '6LfHClQUAAAAAIE5icoMgReVxltCFXzNIa8G4OQ7',
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'keyPrefix' =>  YII_DEBUG ? 'dev_wfnm' : 'prod_wfnm',  //Set To Unique App Abbreviation to prevent conflict with other apps
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            // 'db' => 'db',
            // 'sessionTable' => 'session',
            'writeCallback' => function ($session) {
                return [
                   'user_id' => Yii::$app->user->id,
                   'last_write' => time(),
                ];
            },
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'UTC',
            'dateFormat' => 'php:M-j-Y',
            'datetimeFormat' => 'php:M-j-Y H:i:s',
            'timeFormat' => 'php:H:i:s',
            'nullDisplay' => '',
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
        'assetManager' => [
            'appendTimestamp' => true,
            'linkAssets' => true,
            'hashCallback' => function ($path) {
               return hash('md4', $path);
            },
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        // '//code.jquery.com/jquery-3.2.1.min.js',
                        '//code.jquery.com/jquery-2.2.4.min.js',
                    ],
                ],
                'yii\jui\JuiAsset' => [
                    'sourcePath' => null,   // do not publish the bundle
                    'css'=>[
                        '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css',
                        '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/theme.min.css',
                    ],
                    'js' => [
                        '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js',
                    ],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
                    ],
                ],
                'yii\web\YiiAsset'=>[
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        '//assets.wildfiresnearme.wfmrda.com/js/yii.js',
                    ],
                    'depends'=>[
                        'yii\web\JqueryAsset',
                    ]
                ],
                'yii\grid\GridViewAsset'=>[
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        '//assets.wildfiresnearme.wfmrda.com/js/yii.gridView.js',
                    ],
                    'depends'=>[
                        'yii\web\YiiAsset',
                    ]
                ],
                'yii\widgets\ActiveFormAsset'=>[
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        '//assets.wildfiresnearme.wfmrda.com/js/yii.activeForm.js',
                    ],
                    'depends'=>[
                        'yii\web\YiiAsset',
                    ]
                ],
                'yii\captcha\CaptchaAsset'=>[
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        '//assets.wildfiresnearme.wfmrda.com/js/yii.captcha.js',
                    ],
                    'depends'=>[
                        'yii\web\YiiAsset',
                    ]
                ],
                'common\modules\User\widgets\assets\BirthdayPickerAssets'=>[
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        '//assets.wildfiresnearme.wfmrda.com/js/birthdaypicker.js',
                    ],
                    'depends'=>[
                        'yii\web\YiiAsset',
                    ]
                ],
                'yii\widgets\MaskedInputAsset'=>[
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        '//assets.wildfiresnearme.wfmrda.com/js/jquery.inputmask.bundle.min.js',
                    ],
                    'depends'=>[
                        'yii\web\YiiAsset',
                    ]
                ],
            ],
        ],
        'eauth' => [
            'class' => 'nodge\eauth\EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'cache' => 'cache', // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
            'services' => [ // You can change the providers and their classes.
                'google' => [
                    // register your app here: https://code.google.com/apis/console/
                    'class' => 'common\modules\User\models\eAuth\GoogleOAuth2Service',
                    'clientId'     => '',
                    'clientSecret' => '',
                    'title' => 'Google',
                ],
                'facebook' => [
                    // register your app here: https://developers.facebook.com/apps/
                    'class' => 'common\modules\User\models\eAuth\FacebookOAuth2Service',
                    'clientId'     => '',
                    'clientSecret' => '',
                ],
				'yahoo' => [
					'class' => 'common\modules\User\models\eAuth\YahooOpenIDService',
					'realm' => '', // your domain, can be with wildcard to authenticate on subdomains.
				],
            ],
        ],
        'i18n' => [
            'translations' => [
                'eauth' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@eauth/messages',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'urlManagerFrontEnd' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'http://'.Ptech_HOST,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                    'except' => [
                        'yii\web\HttpException:404',
                    ],
                ],
                [
                    'class' => 'yii\log\DbTarget',
                    'categories' => ['yii\swiftmailer\Logger::add'],
                ],
                [
                    'class' => 'yii\log\DbTarget',
                    'categories' => ['nodge\eauth\*'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'except' => [
                        'yii\web\HttpException:404',
                    ],
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
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['trace'],
                    'categories' => ['mail'],
                    'logVars' => ['_GET'],
                    'logFile' => '@app/runtime/logs/Dev/mail.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 50,
                ],
                /*[
                    'class' => 'yii\log\EmailTarget',
                    'mailer' => 'mailer',
                    'levels' => ['error','warning'],
                    'except' => [
                        'yii\web\HttpException:404',
                    ],
                    'message' => [
                        'from' => ['wildfiresnearme@gmail.com' => 'WFNM Internal Systems'],
                        'to' => ['rgoolsby@firenet.gov'],
                        'subject' => 'WFNM Internal ERROR!!!!!!! Internal Error Message',
                    ],
                ],*/
            ],
        ],
    ],
    'modules'=>[
        'pyrocms' => [
            'class' => 'ptech\pyrocms\PyroCms',
        ],
    ],
];
