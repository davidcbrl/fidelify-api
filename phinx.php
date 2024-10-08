<?php

$dbType = is_string(value: getenv(name: 'DB_TYPE')) ? getenv(name: 'DB_TYPE') : 'mysql';
$dbHost = is_string(value: getenv(name: 'DB_HOST')) ? getenv(name: 'DB_HOST') : 'fidelify-db';
$dbPort = is_numeric(value: getenv(name: 'DB_PORT')) ? getenv(name: 'DB_PORT') : 3306;
$dbName = is_string(value: getenv(name: 'DB_NAME')) ? getenv(name: 'DB_NAME') : 'fidelify';
$dbUser = is_string(value: getenv(name: 'DB_USER')) ? getenv(name: 'DB_USER') : 'root';
$dbPass = is_string(value: getenv(name: 'DB_PASS')) ? getenv(name: 'DB_PASS') : '1234';

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/config/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/config/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => $dbType,
            'host' => $dbHost,
            'name' => $dbName,
            'user' => $dbUser,
            'pass' => $dbPass,
            'port' => $dbPort,
            'charset' => 'utf8',
        ],
        'production' => [
            'adapter' => $dbType,
            'host' => $dbHost,
            'name' => $dbName,
            'user' => $dbUser,
            'pass' => $dbPass,
            'port' => $dbPort,
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation',
];
