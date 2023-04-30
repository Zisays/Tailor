<?php

namespace Frame;

return [
    //默认数据库
    'default' => Env::get('DEFAULT_DATABASE'),
    //mysql 数据库
    'mysql' => [
        'dbms' => Env::get('MYSQL_DBMS'),
        'host' => Env::get('MYSQL_HOST'),
        'port' => Env::get('MYSQL_PORT'),
        'dbname' => Env::get('MYSQL_DBNAME'),
        'user' => Env::get('MYSQL_USER'),
        'pwd' => Env::get('MYSQL_PWD'),
        'pdoAttr' => array()
    ],
    //SqlServer 数据库
    'sqlserver' => [
        'dbms' => Env::get('MSS_DBMS'),
        'Server' => Env::get('MSS_SERVER'),
        'port' => Env::get('MSS_PORT'),
        'Database' => Env::get('MSS_DATABASE'),
        'user' => Env::get('MSS_USER'),
        'pwd' => Env::get('MSS_PWD'),
        'pdoAttr' => array()
    ],
];