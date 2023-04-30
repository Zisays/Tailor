<?php

namespace Frame;

class Api
{
    //允许请求的方法
    private static array $_allowMethod = ['GET', 'POST', 'PUT', 'DELETE'];
    //返回的状态码
    private static array $_statusCode = [
        100 => 'Continue',                          //继续
        101 => 'Switching Protocol',                //协议切换
        200 => 'OK',                                //请求成功
        201 => 'Created',                           //请求成功，并且创建了新的资源
        202 => 'Accepted',                          //已接受(服务器端已经收到请求消息，但是尚未进行处理)
        203 => 'Non-authoritative Information',     //非权威信息
        204 => 'No Content',                        //无内容(请求已经成功了，但是客户端客户不需要离开当前页面)
        205 => 'Reset Content',                     //重置内容(用来通知客户端重置文档视图，比如清空表单内容、重置 canvas 状态或者刷新用户界面)
        206 => 'Partial Content',                   //部分内容(主体包含所请求的数据区间，该数据区间是在请求的 Range 首部指定的)
        300 => 'Multiple Choices',                  //多项选择(请求具有多个可能的响应)
        301 => 'Moved Permanently',                 //永久移动重定向(请求的资源已被永久的移动到新URI，返回信息会包括新的URI，浏览器会自动定向到新URI)
        302 => 'Found',                             //临时移动(与301类似。但资源只是临时被移动。客户端应继续使用原有URI)
        303 => 'See Other',                         //查看其他(重定向不会链接到请求的资源本身，而是链接到另一个页面)
        304 => 'Not Modified',                      //未修改(所请求的资源未修改，服务器返回此状态码时，不会返回任何资源)
        307 => 'Temporary Redirect',                //临时重定向(与302类似,请求的资源已临时移动到 位置标头提供的 URL)
        308 => 'Permanent Redirect',                //永久重定向(与301类似,请求的资源已最终移动到 URL 给出的 URL 位置)
        400 => 'Bad Request',                       //错误请求(客户端请求的语法错误，服务器无法理解)
        401 => 'Unauthorized',                      //未经授权(客户端请求尚未完成，因为它缺少所请求资源的有效身份验证凭据)
        402 => 'Payment Required',                  //需要付款(保留,将来使用.创建此状态代码是为了启用数字现金或（微型）支付系统，并在客户端付款之前请求的内容不可用)
        403 => 'Forbidden',                         //禁止(服务器理解请求，但拒绝授权。例如：对资源的权限不足)
        404 => 'Not Found',                         //未找到(服务器找不到请求的资源)
        405 => 'Method Not Allowed',                //方法不允许(服务器知道请求方法，但目标资源暂不支持此方法)
        406 => 'Not Acceptable',                    //不可接受(服务器无法根据客户端请求的内容特性完成请求)
        407 => 'Authentication Required',           //需要代理身份验证(请求尚未应用，因为它缺少介于浏览器和可以访问所请求资源的服务器)
        408 => 'Request Timeout',                   //请求超时(服务器等待客户端发送的请求时间过长，超时)
        409 => 'Conflict',                          //冲突(服务器处理请求时发生了冲突)
        410 => 'Gone',                              //消失了(客户端请求的资源已经不存在。如果您不知道这种情况是临时的还是永久性的，则应改用 404 状态代码)
        411 => 'Length Required',                   //所需长度(服务器拒绝接受没有定义的内容长度标头的请求)
        412 => 'Precondition Failed',               //前提条件失败(对目标资源的访问已被拒绝，标头定义的条件不是 GET 或 HEAD 以外的方法的条件请求实现)
        413 => 'Content Too Large',                 //内容太大(请求实体大于服务器定义的限制)
        414 => 'URI Too Long',                      //URI 太长(请求的 URI 过长（URI通常为网址）,服务器无法处理)
        415 => 'Unsupported Media Type',            //不支持的媒体类型(服务器拒绝接受请求，因为有效负载格式，格式不受支持)
        416 => 'Range Not Satisfiable',             //范围不满足(服务器无法提供请求的范围)
        417 => 'Expectation Failed',                //期望失败(服务器无法满足 Expect 的请求头信息)
        418 => "I'm a teapot",                      //我是茶壶(服务器拒绝冲泡咖啡，因为它永久是茶壶)
        421 => "Misdirected Request",               //错误的请求(请求已定向到无法生成响应的服务器)
        422 => "Unprocessable Content",             //无法处理的内容(服务器了解请求实体的内容类型，以及 请求实体是正确的，但它无法处理包含的指令)
        423 => "Locked",                            //锁定(资源已锁定，这意味着无法访问它。它的内容应该包含一些WebDAV的XML格式的信息)
        424 => "Failed Dependency",                 //失败的依赖项(无法对资源执行该方法，因为请求的操作依赖于另一个操作，并且该操作失败)
        425 => "Too Early",                         //太早了(服务器不愿意冒险处理请求 这可能会被重放，这会产生重放攻击的可能性)
        426 => "Upgrade Required",                  //需要升级(服务器拒绝使用当前协议执行请求，但可能愿意在客户端升级到其他协议后这样做)
        428 => "Precondition Required",             //需要前提条件(服务器要求请求是有条件的)
        429 => "Too Many Requests",                 //请求过多(用户在给定时间内发送了太多请求)
        431 => "Request Header Fields Too Large",   //请求标头字段太大(服务器拒绝处理请求，因为请求的 HTTP 标头太长。减小请求标头的大小后，可以重新提交请求)
        451 => "Unavailable For Legal Reasons",     //因法律原因不可用(用户请求的资源由于法律原因不可用，例如已对其发出法律诉讼的网页)
        500 => 'Internal Server Error',             //内部服务器错误(服务器遇到意外情况，阻止它完成请求)
        501 => 'Not Implemented',                   //未实现(服务器不支持完成请求所需的功能)
        502 => 'Bad Gateway',                       //错误的网关(服务器在充当网关或代理时收到来自上游服务器的无效响应)
        503 => 'Service Unavailable',               //服务不可用(服务器尚未准备好处理请求)
        504 => 'Gateway Timeout',                   //网关超时(服务器在充当网关或代理时，未及时从上游服务器获得完成请求所需的响应)
        505 => 'HTTP Version Not Supported',        //不支持的 HTTP 版本(服务器不支持请求中使用的 HTTP 版本)
        506 => 'Variant Also Negotiates',           //变体也协商(在透明内容协商的上下文中给出协商响应状态代码)
        507 => 'Insufficient Storage',              //存储空间不足(可以在 Web 分布式创作和版本控制 （WebDAV） 协议的上下文中给出)
        508 => 'Loop Detected',                     //循环检测到(可以在 Web 分布式创作和 版本控制 （WebDAV） 协议)
        510 => 'Not Extended',                      //未扩展
        511 => 'Network Authentication Required',   //需要网络身份验证(客户端需要进行身份验证才能获得网络访问权限)
    ];

    /**
     * 运行
     * @return void
     */
    public static function run(): void
    {
        include_once 'config/api.php';
        self::error('请求的 Api 接口不存在', 404);
    }

    /**
     * 返回一个成功地响应数据方法和格式
     * @param array $data
     * @param string $msg
     * @param int $code
     * @return void
     */
    public static function success(mixed $data, string $msg = 'success', int $code = 200): void
    {
        self::json($data, $msg, $code);
    }

    /**
     * 返回一个错误地响应数据方法和格式
     * @param mixed $msg
     * @param int $code
     * @return void
     */
    public static function error(mixed $msg = 'error', int $code = 400): void
    {
        if ($msg == 'error' and !empty(self::$_statusCode[$code])) {
            $msg = self::$_statusCode[$code];
        }
        self::json([], $msg, $code);
    }

    public static function get($url, $action, $version): void
    {
        self::add('GET', $url, $action, $version);
    }

    public static function post($url, $action, $version): void
    {
        self::add('POST', $url, $action, $version);
    }

    public static function put($url, $action, $version): void
    {
        self::add('PUT', $url, $action, $version);
    }

    public static function delete($url, $action, $version): void
    {
        self::add('DELETE', $url, $action, $version);
    }

    /**
     * 注册路由
     * @param string $methods
     * @param string $url
     * @param string $action
     * @param string $version
     * @return void
     */
    private static function add(string $methods, string $url, string $action, string $version = 'v1'): void
    {
        //判断请求方式是否允许
        if (in_array($methods, self::$_allowMethod) and $methods == $_SERVER['REQUEST_METHOD']) {
            //参数处理
            $param = self::parameter($methods, $url, $version);
            if ($param) {
                $action = explode('@', $action);
                $file = '\Api\\' . $version . '\\' . $action[0];
                $function = $action[1];
                $controller = new $file;
                $controller->$function();
            }
        }
    }

    /**
     * 参数处理
     * @param $methods
     * @param $url
     * @param $version
     * @return array|false|mixed|string
     */
    public static function parameter($methods, $url, $version): mixed
    {
        $pathInfo = trim($_SERVER['PATH_INFO'], '/');
        $uri = preg_replace('/({\w+})/', '(\d+)', trim($url, '/'));
        $preg = '/^' . $version . '\/' . preg_replace('/\//', '\/', $uri) . '$/';
        if (preg_match($preg, $pathInfo, $arr)) {
            switch ($methods) {
                case 'DELETE':
                case 'GET':
                    $url = explode('/', $arr[0]);
                    if (count($url) > 1) {
                        $i = 2;
                        while ($i < count($url)) {
                            if (isset($url[$i + 1])) {
                                $_GET[$url[$i]] = $url[$i + 1];
                            }
                            $i = $i + 2;
                        }
                    }
                    break;
                case 'PUT':
                case 'POST':
                    $_POST = json_decode(file_get_contents('php://input'), true);
                    break;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 输出 JSON
     * @param mixed $data
     * @param mixed $msg
     * @param int $code
     * @return void
     */
    private static function json(mixed $data, mixed $msg = '', int $code = 200): void
    {
        if ($code !== 200 and $code > 200) {
            header('HTTP/1.1 ' . $code . ' ' . self::$_statusCode[$code]);
        }
        $datetime = date('Y-m-d H:i:s');
        header("Content-Type:application/json;charset=utf-8");
        $response = ['time' => $datetime, 'code' => $code, 'msg' => $msg, 'data' => $data];
        exit(json_encode($response, JSON_UNESCAPED_UNICODE));
    }
}