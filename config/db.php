<?php

return [
    'class' => 'yii\db\Connection',
    // 'dsn' => 'mysql:host=backupdev-postgres-1;dbname=backupdb',
    'dsn' => 'pgsql:host=backupdev-postgres-1;port=5432;dbname=backupdb',
    'username' => 'postgres',
    'password' => 'postgres',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
