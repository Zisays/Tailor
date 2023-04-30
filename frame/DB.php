<?php

namespace Frame;

class DB
{
    public static function connect($default = 'default')
    {
        $config = include('config/database.php');
        if (array_key_exists($default, $config)) {
            if ($default == 'default') {
                $type = $config[$default];
            } else {
                $type = $default;
            }
            $pdo = null;
            switch ($config[$type]['dbms']) {
                case 'mysql':
                    $dsn = 'mysql:host=' . $config[$type]['host'] . ';';
                    $dsn .= 'port=' . $config[$type]['port'] . ';';
                    $dsn .= 'dbname=' . $config[$type]['dbname'] . ';';
                    $username = $config[$type]['user'];
                    $password = $config[$type]['pwd'];
                    $driver_options = $config[$type]['pdoAttr'];
                    $pdo = new DataBase($dsn, $username, $password, $driver_options);
                    break;
                case 'sqlsrv':
                    $dsn = 'sqlsrv:Server=' . $config[$type]['Server'];
                    if (!empty($config[$type]['port'])) {
                        $dsn .= ',' . $config[$type]['port'] . ';';
                    } else {
                        $dsn .= ';';
                    }
                    $dsn .= 'Database=' . $config[$type]['Database'] . ';';
                    $username = $config[$type]['user'];
                    $password = $config[$type]['pwd'];
                    $driver_options = $config[$type]['pdoAttr'];
                    $pdo = new DataBase($dsn, $username, $password, $driver_options);
                    break;
                default:
            }
            return $pdo;
        } else {
            if (!empty($config[$default])) {
                trigger_error('没有找到键名为【' . $config[$default] . '】的数据库配置信息!');
            } else {
                trigger_error('没有找到键名为【' . $default . '】的数据库配置信息!');
            }
        }
        return false;
    }
}