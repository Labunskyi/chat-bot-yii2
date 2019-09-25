<?php
$params = require __DIR__ . '/params.php';
( new \Dotenv\Dotenv( __DIR__ . '/..' ) )->load();
$db = require __DIR__ . '/db.php';

$config = [
    'id'         => 'basic',
    'basePath'   => dirname( __DIR__ ),
    'bootstrap'  => [ 'log' ],
    'language'   => 'ru',
    'name'       => getenv( 'APP_NAME' ),
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'i18n'                 => [
            'translations' => [
                'app*'      => [
                    'class'          => 'yii\i18n\PhpMessageSource',
                    'basePath'       => '@app/language',
                    'sourceLanguage' => 'en',
                ],
                'landing*'  => [
                    'class'          => 'yii\i18n\PhpMessageSource',
                    'basePath'       => '@app/language',
                    'sourceLanguage' => 'en',
                ],
                'facebook*' => [
                    'class'          => 'yii\i18n\PhpMessageSource',
                    'basePath'       => '@app/language',
                    'sourceLanguage' => 'en',
                ],
                'telegram*' => [
                    'class'          => 'yii\i18n\PhpMessageSource',
                    'basePath'       => '@app/language',
                    'sourceLanguage' => 'en',
                ],
            ],
        ],
        'assetManager'         => [
            'class'   => 'yii\web\AssetManager',
            'bundles' => [
                'yii\web\JqueryAsset'                => [
                    'js' => [
                        'jquery.min.js',
                    ],
                ],
                'yii\bootstrap\BootstrapAsset'       => [
                    'css' => [
                        //'css/bootstrap.min.css',
                    ],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        'js/bootstrap.min.js',
                    ],
                ],
            ],
        ],
        'request'              => [
            'baseurl'             => '',
            'cookieValidationKey' => 'Bmesdfsdfsdfsdf8Pl1kCb5N_8eBbhK8sdfsdasfWSjKIZMCFRP7',
        ],
        'cache'                => [
            'class' => 'yii\caching\FileCache',
        ],
        'user'                 => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl'        => [ 'user/login' ],
        ],
        'errorHandler'         => [
            'errorAction' => 'site/error',
        ],
        'mailer'               => [
            'class'            => 'yii\swiftmailer\Mailer',
            'viewPath'         => '@app/mail',
            'htmlLayout'       => 'layouts/html',
            'textLayout'       => 'layouts/text',
            'messageConfig'    => [
                'charset' => 'UTF-8',
                'from'    => [ 'info@site.com' => 'Info' ],
            ],
            'useFileTransport' => false,

        ],
        'log'                  => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => [ 'error', 'warning' ],
                ],
            ],
        ],
        'db'                   => $db,
        'urlManager'           => [
            'class'                        => 'codemix\localeurls\UrlManager',
            'languages'                    => [ 'ru' ],
            'enablePrettyUrl'              => true,
            'showScriptName'               => false,
            'enableLanguageDetection'      => false,
            'enableDefaultLanguageUrlCode' => true,
            'rules'                        => [
                '/'                                     => 'site/index',
                'login'                                 => 'user/login',
                'signup'                                => 'user/signup',
                'bots/edit/<id>'                        => 'bots/edit',
                'dashboard/<base_id>'                   => 'bots/bot-view',
                'newsletter/<base_id>'                  => 'newsletter/list',
                'newsletter/<base_id>/edit/<id>'        => 'newsletter/edit',
                'newsletter/<base_id>/delete/<id>'      => 'newsletter/delete',
                'newsletter/<base_id>/add'              => 'newsletter/add',
                'newsletter/<base_id>/update-send-user' => 'newsletter/update-send-user',
                'newsletter/<base_id>/deactivate/<id>'  => 'newsletter/deactivate',
                'customer/<base_id>'                    => 'customer/list',
                'customer/<base_id>/edit/<id>'          => 'customer/edit',
                'customer/link-profiles/<base_id>'      => 'customer/link-profiles',
                'customer/un-link-profiles/<base_id>'   => 'customer/un-link-profiles',
                'chats/<base_id>'                       => 'chat/list',
                'hook/<user_id>/<bot_id>/<sign>'        => 'hook/index',
                'widget/iframe/<base_id>'               => 'widget/generate-iframe',
                'widget/js/<base_id>'                   => 'widget/generate-js',
                'widget/<base_id>/settings'             => 'widget/settings',

                'category/delete'              => 'category/delete',
                'category/<base_id>'           => 'category/list',
                'category/<base_id>/add'       => 'category/add',
                'category/<base_id>/edit/<id>' => 'category/edit',

                'product/delete'              => 'product/delete',
                'product/<base_id>'           => 'product/list',
                'product/<base_id>/add'       => 'product/add',
                'product/<base_id>/edit/<id>' => 'product/edit',

                'delivery/delete'              => 'delivery/delete',
                'delivery/<base_id>'           => 'delivery/list',
                'delivery/<base_id>/add'       => 'delivery/add',
                'delivery/<base_id>/edit/<id>' => 'delivery/edit',

                'delivery-point/delete'              => 'delivery-point/delete',
                'delivery-point/<base_id>'           => 'delivery-point/list',
                'delivery-point/<base_id>/add'       => 'delivery-point/add',
                'delivery-point/<base_id>/edit/<id>' => 'delivery-point/edit',

                'payment/delete'              => 'payment/delete',
                'payment/<base_id>'           => 'payment/list',
                'payment/<base_id>/add'       => 'payment/add',
                'payment/<base_id>/edit/<id>' => 'payment/edit',

                'menu/delete'              => 'menu/delete',
                'menu/<base_id>'           => 'menu/list',
                'menu/<base_id>/add'       => 'menu/add',
                'menu/<base_id>/edit/<id>' => 'menu/edit',

                'command/delete'              => 'command/delete',
                'command/<base_id>'           => 'command/list',
                'command/<base_id>/add'       => 'command/add',
                'command/<base_id>/edit/<id>' => 'command/edit',

                'temp/<base_id>' => 'temp/index',

                'orders/<base_id>'           => 'order/list',
                'orders/<base_id>/edit/<id>' => 'order/edit',

                'setting/<base_id>/bot/<bot_id>' => 'bots/setting',

                '<controller>/<action>' => '<controller>/<action>',
            ],
        ],
        'authClientCollection' => [
            'class'   => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class'        => 'yii\authclient\clients\Facebook',
                    'clientId'     => getenv( 'FB_CLIENT_ID' ),
                    'clientSecret' => getenv( 'FB_CLIENT_SECRET' ),
                ],
            ],
        ],

    ],
    'params'     => $params,
];


if ( YII_ENV_DEV ) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
