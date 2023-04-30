<?php

/**
 * 自动加载类
 */
class  Autoload
{
    /**
     * 命名空间 => 所属文件夹路径
     * @var string[]
     */
    public static array $vendorMap = [
        'Api' => 'api',
        'Frame' => 'frame',
    ];

    /**
     * @param $class
     * @return void
     */
    public static function run($class): void
    {
        $vendor = explode('\\', $class);
        if (array_key_exists($vendor[0], self::$vendorMap)) {
            $vendorDir = __DIR__ . DIRECTORY_SEPARATOR . self::$vendorMap[$vendor[0]];
            if (file_exists($vendorDir)) {
                $file = strtr($vendorDir . substr($class, strlen($vendor[0])) . '.php', '\\', DIRECTORY_SEPARATOR);
                if (file_exists($file)) {
                    if (is_file($file)) {
                        include $file;
                    }
                }
            } else {
                trigger_error('在自动加载类中，没有找到【' . $vendorDir . '】目录!');
            }
        } else {
            trigger_error('在自动加载类中，没有配置【' . $vendor[0] . '】的命名空间路径!');
        }
    }

    public static function load(): void
    {
        include 'function.php';
    }
}