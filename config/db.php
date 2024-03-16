<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=fault_tree_db;',
    'username' => 'postgres',
    'password' => '12345',
    'charset' => 'utf8',
    'tablePrefix' => 'kms_',
    'schemaMap' => [
        'pgsql'=> [
            'class'=>'yii\db\pgsql\Schema',
            'defaultSchema' => 'public'
        ]
    ],
];

