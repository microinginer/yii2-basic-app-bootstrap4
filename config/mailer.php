<?php return [
    'class' => \yii\symfonymailer\Mailer::class,
    'viewPath' => '@app/mail',
    'transport' => [
        'host' => getenv('SMTP_HOST'),
        'username' => getenv('SMTP_LOGIN'),
        'password' => getenv('SMTP_PASSWORD'),
        'port' => '587',
        'encryption' => 'tls',
        'dsn' => 'native://default',
    ],
    'useFileTransport' => !YII_ENV_DEV,
];
