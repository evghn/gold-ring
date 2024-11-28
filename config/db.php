<?php

if (strpos(__DIR__, '6') !== false) {
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=MariaDB-11.2;dbname=gold_ring',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    
        // Schema cache options (for production environment)
        //'enableSchemaCache' => true,
        //'schemaCacheDuration' => 60,
        //'schemaCache' => 'cache',
    ];

} else {
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;port=33061;dbname=gold_ring',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    
        // Schema cache options (for production environment)
        //'enableSchemaCache' => true,
        //'schemaCacheDuration' => 60,
        //'schemaCache' => 'cache',
    ];
}
