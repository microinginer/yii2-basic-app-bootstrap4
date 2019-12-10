<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => getenv('DB_DSN'),
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
    'charset' => 'utf8',

    'enableSchemaCache' => YII_ENV_PROD,
    'schemaCacheDuration' => 86400,
    'schemaCache' => 'cache',
];
