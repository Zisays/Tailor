<?php

namespace Frame;

use Exception;

class Error
{
    /**
     * 调试模式（true:开，false：关）
     * @return void
     */
    public static function startUsing(): void
    {
        error_reporting(E_ALL);
        if (ENV::get('DEBUG')) {
            //开启错误信息输出到页面
            ini_set('display_errors', '1');
        } else {
            //关闭错误信息输出到页面
            ini_set('display_errors', '0');
            //设置错误日志保存路径
            ini_set('error_log', ROOT . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log');
            //开启错误日志记录
            ini_set('log_errors', '1');
            //不重复记录出现在同一个文件中的同一行代码上的错误信息。
            ini_set('ignore_repeated_errors', 1);
        }
    }

    /**
     * 设置用户自定义的错误处理函数
     * @param int $errorLevel
     * @param string $errorMessage
     * @param string $errorFile
     * @param int $errorLine
     */
    public static function error_handler(int $errorLevel, string $errorMessage, string $errorFile = '?', int $errorLine = 0): void
    {
        if (ENV::get('DEBUG')) {
            $error = [
                '错误级别' => $errorLevel,
                '错误信息' => $errorMessage,
                '错误文件' => $errorFile,
                '错误行号' => $errorLine,
            ];
        } else {
            $error = $errorMessage;
        }
        Api::error($error);
    }

    /**
     * 设置用户自定义运行结束时的错误
     * @return void
     * @throws Exception
     */
    public static function error_end(): void
    {
        $egl = error_get_last();
        if (!empty($egl['type']) and ($egl['type'] > 1)) {
            $egl_type = match ($egl['type']) {
                1 => '致命的运行时错误(E_ERROR)',
                2 => '非致命的运行时错误(E_WARNING)',
                4 => '编译时语法解析错误(E_PARSE)',
                8 => '运行时提示(E_NOTICE)',
                16 => 'PHP内部错误(E_CORE_ERROR)',
                32 => 'PHP内部警告(E_CORE_WARNING)',
                64 => 'Zend脚本引擎内部错误(E_COMPILE_ERROR)',
                128 => 'Zend脚本引擎内部警告(E_COMPILE_WARNING)',
                256 => '用户自定义错误(E_USER_ERROR)',
                512 => '用户自定义警告(E_USER_WARNING)',
                1024 => '用户自定义提示(E_USER_NOTICE)',
                2048 => '代码提示(E_STRICT)',
                4096 => '可以捕获的致命错误(E_RECOVERABLE_ERROR)',
                8191 => '所有错误警告(E_ALL)',
                default => '未知类型',
            };
            $msg = PHP_EOL . '错误日期：' . '【 ' . date("Y-m-d H:i:s") . ' 】' . PHP_EOL;
            $msg .= '错误级别：' . $egl_type . PHP_EOL;
            $msg .= '错误信息：' . $egl['message'] . PHP_EOL;
            $msg .= '错误文件：' . $egl['file'] . PHP_EOL;
            $msg .= '错误行号：' . $egl['line'] . PHP_EOL . PHP_EOL;
            error_log($msg, 0);
        }
    }
}