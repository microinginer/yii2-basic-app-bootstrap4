<?php

use app\controllers\RegistrationController;
use app\controllers\RecoveryController;
use app\models\user\User;
use Da\User\Controller\ProfileController;
use Da\User\Controller\SecurityController;
use Da\User\Controller\SettingsController;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$mailer = require __DIR__ . '/mailer.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','adminable'],
    'name' => Yii::t('app', 'My application'),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'language' => 'ru',
    'sourceLanguage' => 'ru-RU',
    'modules' => [
        'user' => [
            'class' => Da\User\Module::class,
            // ...other configs from here: [Configuration Options](installation/configuration-options.md), e.g.
            // 'generatePasswords' => true,
            // 'switchIdentitySessionKey' => 'myown_usuario_admin_user_key',
            'administratorPermissionName' => 'admin',
            'layout' => '@app/modules/adminable/views/layouts/main',
            'viewPath' => '@app/views/user',
            'enableFlashMessages' => true,
            'enableEmailConfirmation' => true,
            'enableSwitchIdentities' => true,
            'enableGdprCompliance' => true,
            'gdprPrivacyPolicyUrl' => ['/site/privacy'],
            'classMap' => [
                'User' => User::class,
            ],
            'controllerMap' => [
                'security' => [
                    'class' => SecurityController::class,
                    'layout' => '@app/views/layouts/sign'
                ],
                'registration' => [
                    'class' => RegistrationController::class,
                    'layout' => '@app/views/layouts/sign'
                ],
                'recovery' => [
                    'class' => RecoveryController::class,
                    'layout' => '@app/views/layouts/sign'
                ],
                'settings' => [
                    'class' => SettingsController::class,
                    'layout' => '@app/views/layouts/main'
                ],
                'profile' => [
                    'class' => ProfileController::class,
                    'layout' => '@app/views/layouts/main'
                ],
            ],
        ],
        'adminable' => [
            'class' => 'app\modules\adminable\Module',
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'a548d7b4ac54f0923424696148d7d0e4',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\user\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => $mailer,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@Da/User/resources/views' => '@app/views/user'
                ]
            ]
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
        'generators' => [
            'crud' => [
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => [
                    'appCrud' => '@app/gii/crud/default',
                ],
                'template' => 'appCrud',
            ],
            'model' => [
                'class' => 'yii\gii\generators\model\Generator',
                'templates' => [
                    'appModel' => '@app/gii/model/default',
                ],
                'template' => 'appModel',
            ],
        ],
    ];
}

return $config;
