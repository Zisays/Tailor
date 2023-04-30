# Tailor
Tailor-made Api framework

# 一、简介

欢迎您使用 Tailor

![](https://img.shields.io/badge/Frame-Tailor-red)   ![PHP](https://img.shields.io/badge/php->%3D8.0-brightgreen)   ![license](https://img.shields.io/badge/license-Apache--2.0-blue)   

**Github**

> 项目地址：https://github.com/Zisays/Tailor   		（ 优先更新，推荐 Star ⭐ ）
>

**码云：**

> 项目地址：https://gitee.com/zisay/Tailor 	（ 国内访问速度快 ）

若您有任何疑问或建议反馈，都可以通过以下方式联系到我，我非常愿意倾听您的建议与观点！

> QQ：15593838 
>
> Email：zisayzhang@outlook.com
>
> gmail：zisayzhang@gmail.com

**友情赞助**

1、支付宝（ 推荐 ）

 <img src="sponsor/支付宝.jpg" alt="支付宝" width="30%" style='border:3px solid #1678FF;'/>

2、微信

<img src="sponsor/微信.jpg" alt="微信" width="30%" style='border:3px solid #07C160;'/> 

# 二、开发规范

## 1、域名

> 应该将 `Api` 接口部署在二级域名下
>

```apl
http://api.zisay.cn
```

## 2、版本号

> 应该将 `Api` 的版本号 `v1`（ version 1 的简写）放入 `url` 中
>

```apl
http://api.zisay.cn/v1/
```

## 3、资源

每次请求都是对资源的操作，而不是像以前那样要写多个方法

（ X ）错误的

```apl
http://api.zisay.cn/getUser
http://api.zisay.cn/postUser
http://api.zisay.cn/putUser
http://api.zisay.cn/deleteUser
```

（ V ）正确的

```apl
http://api.zisay.cn/v1/users
```

# 三、请求方式

## 1、获取资源（GET）

```apl
http://api.zisay.cn/v1/users/1
```

## 2、传输实体主体（POST）

```apl
http://api.zisay.cn/v1/users
```

```apl
POST /index.php HTTP/1.1
Host: localhost
Content-Type:application/json;charset=utf-8

name=”zisay”&qq=”15593838”
```

## 3、更新请求（PUT）

```apl
http://api.zisay.cn/v1/users
```

```apl
PUT /index.php HTTP/1.1
Host: localhost
Content-Type:application/json;charset=utf-8

name=”zisay”&qq=”15593838”
```

## 4、删除请求（DELETE）

```apl
http://api.zisay.cn/v1/users/1
```

## 5、过滤信息

（1）、返回指定记录的数量

> 理解：从当前请求的结果中，返回前 10 条记录

```apl
http://api.zisay.cn/v1/users?limit=10
```

（2）、返回指定记录的开始位置

> 理解：从当前请求的结果中，向下偏移10位后，返回其他的记录

```apl
http://api.zisay.cn/v1/users?offset=10
```

（3）、指定第几页，以及每页的记录数

> 理解：当前请求的每页条数为 100 条，需要返回第 2 页的数据

```apl
http://api.zisay.cn/v1/users?page=2&per_page=100
```

（4）、指定返回结果按照哪个属性排序，以及排序顺序。

> 理解：从当前请求的结果中，指定需要进行排序的字段，并设置正序或者倒序排列

```apl
http://api.zisay.cn/v1/users?sortby=name&order=asc
```

（5）、指定筛选条件

> 理解：也就是指定过滤条件
>
> 建议：过滤条件的字段不要和前 4 条中的过滤字段名重复

```apl
http://api.zisay.cn/v1/users?id=1
http://api.zisay.cn/v1/users?name=zisay
http://api.zisay.cn/v1/users?qq=15593838
http://api.zisay.cn/v1/users?id=1&name=zisay&qq=15593838
```

# 四、函数

## 1、x

> 说明：输出数据并终止执行
>
> 参数：$data 可以传递多个变量

例：

```php
<?php

//1、输出数值
x(1);

//2、输出字符串
x('1');

//3、输出数组
x(array('123','456','789'));

//4、输出多个变量的值
$a = '1';
$b = '2';
x($a, $b);
```

# 五、方法

## 1、Api::success

> 说明：该函数返回 Api 成功响应信息
>
> 参数：
>
> $data （注：返回的时候会自动将数组转换为 json ）
>
> $msg 提示信息	（ 可选，默认提示信息为:success ）

例子：

```php
<?php

namespace Api\v1;

use Frame\Api;

class Article
{
    public function select(): void
    {
        $data = ['1','2','3'];
        Api::success($data);
        
        //如果需要返回自定义提示信息，可以使用下面的写法
        //Api::success($data,'请求成功了!')
    }
}
```

## 2、Api::error

> 说明：该函数返回 Api 失败响应信息
>
> 参数：
>
> $msg 提示信息（ 可选，默认提示信息为:error ）
>
> $code 错误码（ 可选，默认为：400）

例子：

```php
<?php

namespace Api\v1;

use Frame\Api;

class Article
{
    public function select(): void
    {
        Api::error();
        
        //如果需要返回错误码和自定义提示信息，可以使用下面的写法
        //Api::success('请求失败了!',400)
    }
}
```

## 3、Page::data

> 说明：返回分页数据及分页信息
>
> 参数：
>
> $db：数据库实例对象
>
> $table：表名
>
> $page：当前点击的页数
>
> $per_page：当前页面显示多少条数据
>
> $show_page：当前页面显示多少个页码

例子：

```php
<?php

namespace Api\v1;

use Frame\Api;
use Frame\Page;

class Article
{
    public function select(): void
    {
        $db = DB::connect();
        $data = Page::Data($db, 'users', $_GET['page'], $_GET['per_page']);
        Api::success($data);
    }
}
```

# 六、数据库

> 数据库语法就是通用的 SQL 语法，不必在再学什么语法糖，只要你会写 SQL 就会用 PHP 操作数据库
>
> 目前已支持的数据库有：MySQL、SqlServer

## 1、数据库配置

数据库配置主要是修改 `.env` 文件和 `config/database.php` 文件，就可以了

### （1）、.env

`.env` 文件是 "环境变量配置文件"，数据库的连接信息一般都是放到这个文件里。

它的作用也很简单，就是方便不同环境的部署使用

比如你创建两个 env 文件，一个叫 `local.env（本地环境配置文件）` 一个叫 `server.env（服务器环境配置文件）`

这样话，你就不用每次部署或者拉到本地测试的时候还要修改配置了。

当然，暂时对我来说，一个 `.env` 文件就已经足够了

**【1】、debug**

debug 参数的意思是 "调试模式"

```
# 调试模式
DEBUG=true
```

当 `debug=true` 时会打开调试模式，将错误信息输出到页面上

当 `debug=fasle` 时会关闭调试模式，并将错误或者异常信息写入到 `log` 文件夹下

**【2】、DEFAULT_DATABASE**

DEFAULT_DATABASE 参数的意思是 "默认数据库"

```php
# 默认数据库
DEFAULT_DATABASE=mysql
```

在第 2 章实例化中，有一段代码是这样写的

```php
<?php
$db = DB::connect();
```

`DB::connect()` 括号中如果不填写的话，就会获取默认数据库的信息

也就是 database.php 下数组主键为 default 的值

```php
'default' => Env::get('DEFAULT_DATABASE')
```

然后，default 的值是通过 Env 函数获取的，也就是当前根目录下的 `.env` 文件下的 `DEFAULT_DATABASE` 配置

**【3】、MySQL**

接下来就是配置 MySQL 的信息了，当然，这里不是固定写死的，你可以随便写，只要你在 database.php 文件中，用 Env 函数获取到对应的 key 就可以了

```php
#【MySQL】
MYSQL_DBMS=mysql
MYSQL_HOST=127.0.0.1
MYSQL_PORT=3306
MYSQL_DBNAME=zisay
MYSQL_USER=root
MYSQL_PWD=root
```

**【4】、SqlServer**

> 与第 3 个 MySQL 的配置大致相同，只是名称我改了一下，如果有需求，你也可以修改成其他名称

```php
#【SqlServer】
MSS_DBMS=sqlsrv
MSS_SERVER=127.0.0.1
MSS__PORT=1433
MSS_DATABASE=zisay
MSS_USER=sa
MSS_PWD=123456
```

### （2）、database.php

database.php 配置文件，其实就是返回了一个数组

在了解了上一章 env 文件的配置后，可以看到这里读取 env 文件的配置只需要使用  `Env::get()` 方法就可以了

> 注意：SqlServer 如果不是默认实例，那就要修改端口号。可以从 【SqlServer 配置管理器——>SqlServer 网络配置——>SQL2016 的协议——>TCP/IP——>IP 地址】中，使用 IPALL 下面的 TCP Dynamic Ports 中的端口号即可

```php
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
```

如果需要连接多个数据库的话，直接在数组中添加即可

```php
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
     //mysql666 数据库
    'mysql666' => [
        'dbms' => Env::get('MYSQL666_DBMS'),
        'host' => Env::get('MYSQL666_HOST'),
        'port' => Env::get('MYSQL666_PORT'),
        'dbname' => Env::get('MYSQL666_DBNAME'),
        'user' => Env::get('MYSQL666_USER'),
        'pwd' => Env::get('MYSQL666_PWD'),
        'pdoAttr' => array()
    ],
];
```

然后在实例化的时候，用 `指定数据库` 的方式，指定它就可以了

```php
<?php
$db = DB::connect('mysql'); //连接 mysql 数据库
$db = DB::connect('mysql666'); //连接 mysql666 数据库
```

## 2、实例化

### （1）、默认数据库

```php
<?php
//在括号不填写内容，会自动读取默认数据库连接信息
//也就是 config/database.php 文件下数组主键为 default 的值
//再查找主键为 default 值的数据库连接信息
$db = DB::connect();
```

### （2）、指定数据库

```php
<?php
$db = DB::connect('mysql');	//实例化 config/database.php 文件下数组主键为 mysql 的连接信息
$db1 = DB::connect('mysql1'); //实例化 config/database.php 文件下数组主键为 mysql1 的连接信息
$db2 = DB::connect('mysql2'); //实例化 config/database.php 文件下数组主键为 mysql2 的连接信息
```

## 3、CRUD

### （1）、增

```php
<?php
    
//实例化默认数据库
$db = DB::connect();

$data = ['name'='zisay','qq'='15593838']
$db->insertInto('user')->value($data)->run();
```

### （2）、删

```php
<?php
    
//实例化默认数据库
$db = DB::connect();

//删除所有数据
$db->deleteFrom('user')->run();

//删除 id = 1 的数据
$db->deleteFrom('user')->where('id','1')->run();		//语法1
$db->deleteFrom('user')->where('id','=','1')->run();	//语法2

//删除 id=1 并且 name=zisay 的数据
$db->deleteFrom('user')
    ->where('id','=','1')
    ->and('name','=','zisay')
    ->run();
```

### （3）、改

```php
<?php
    
//实例化默认数据库
$db = DB::connect();

$data = ['name'='zisay','qq'='15593838']

//修改所有数据
$db->update('user')->set($data)->run();

//修改 id = 1 的数据
$db->update('user')->set($data)->where('id','1')->run();

//修改 id = 1 并且 name='zisay' 的数据
$db->update('user')
    ->set($data)
    ->where('id','=','1')
    ->and('name','=','zisay')
    ->run();
```

### （4）、查

**【1】、select**

> 参数：$column：要查询或者要计算的字段名（可选， 默认值为 * ）

```php
<?php
//实例化默认数据库
$db = DB::connect();

//1、查询 user 表所有数据
$db->select()->from('user')->run();

//2、查询 user 表中字段为 `account` 和 `password` 的所有数据
$db->select('account,password')->from('user')->run();

//3、返回本次查询的总条数
$db->select('count(*) as count')->from('user')->run();

//4、返回 views 字段的的总合计
$count = $db->select('sum(views) as sum')->from('article')->run();
```

**【2】、count**

> 参数：$table：要查询的表名或者视图名（必填）

```php
<?php
//实例化默认数据库
$db = DB::connect();

//查询 user 表总条数
$db->count('users');
```

**【3】、from**

> 参数：$table：要查询的表名或者视图名（必填）

```php
<?php
//实例化默认数据库
$db = DB::connect();

//查询 user 表所有数据
$db->select()->from('user')->run();
```

**【4】、where**

```php
<?php
//实例化默认数据库
$db = DB::connect();

//1、查询 user 表中 id = 1 的数据
$db->select()->from('user')->where('id',1)->run();

//2、查询 user 表中 sex = 1 并且 age > 18 的数据
$db->select()->from('user')->where('sex','=',1)->and('age','>',18)->run();

//3、查询 user 表中 account = 123 或者 account = 456 的数据
$db->select()->from('user')->where('account',123)->or('account',456)->run();
```

**【5】、in**

```php
<?php
//实例化默认数据库
$db = DB::connect();

//查询 user 表中 id 包含 24和25 的数据
$db->select()->from('user')->where('id', 'in', [24,25])->run();
```

**【6】、like**

> 模糊查询

```php
<?php
//实例化默认数据库
$db = DB::connect();

//1、查询 name 字段以 google 开头的数据
$db->select()->from('user')->where('name','like','google%')->run();

//2、查询  name 字段以 google 结尾的数据
$db->select()->from('user')->where('name','like','%google')->run();

//3、查询 name 字段中包含 google 的数据
$db->select()->from('user')->where('name','like','%google%')->run();

//4、查询 name 字段中 5 位数，开头是 g 最后一位为 e 的数据
$db->select()->from('user')->where('name','like','%g____e%')->run();
```

**【7】、regexp**

> 正则表达式查询

```php
<?php
//实例化默认数据库
$db = DB::connect();

//1、查询 `name` 字段以 `goo` 开头的数据
$db->select()->from('user')->where('name','regexp','^[goo]')->run();

//2、查询 `name` 字段不以 `goo` 开头的数据
$db->select()->from('user')->where('name','regexp','^[^goo]')->run();

//3、查询 `name` 字段不以 `a-z` 开头的数据
$db->select()->from('user')->where('name','regexp','^[^a-z]')->run();
```

## 4、通用方法

### （1）、事务（ trans ）

```php
//开启一个事务 {关闭自动提交模式，启用手动 commit 提交}
$db->begin();
//修改一条数据
$db->update('user')->set(['name' => '张三'])->where('sex', '1');
//判断事务是否存在
if ($db->inTrans()) {
    //提交事务 {提交一个事务，并恢复自动提交模式}
    $db->commit();
} else {
    //回滚事务 {回滚一个事务，并恢复自动提交模式}
    $db->rollback();
}
```

### （2）、查询（ query ）

> 直接执行一个 `SQL`  查询语句，并返回一个数组

```php
//直接执行一条查询语句 { select }
$db->query("select * from user");
```

### （3）、执行（ exec ）

> 直接执行一个 `SQL` 语句，并返回受影响的条数

```php
//直接执行一条插入语句 { insert }
$db->exec("insert into user(name,sex,age) values('张三',1,18)");
//直接执行一条更新语句 { update }
$db->exec("update user set sex = 0 where name = '张三'");
//直接执行一条删除语句 { delete }
$db->exec("delete from user where name = '张三'");
```

### （4）、获取 `PDO` 当前连接属性的值

```php
//PDO::ATTR_AUTOCOMMIT { 是否自动提交每个单独的语句 }
//	1 = 自动提交 { 默认值 }
//	0 = 手动提交
$db->getAttr(PDO::ATTR_AUTOCOMMIT);

//PDO::ATTR_CASE	{ 强制列名为指定的大小写 }
//	0 = PDO::CASE_NATURAL 	{ 保留数据库驱动返回的列名 } { 默认值 }
//	1 = PDO::CASE_UPPER 	{ 强制列名大写 }
//  2 = PDO::CASE_LOWER		{ 强制列名小写 }
$db->getAttr(PDO::ATTR_CASE);

//PDO::ATTR_CLIENT_VERSION { 返回当前 PDO 驱动所用客户端库的版本信息 } { 只读属性 }
$db->getAttr(PDO::ATTR_CLIENT_VERSION);

//PDO::ATTR_CONNECTION_STATUS { 返回当前 PDO 连接状态 } { 只读属性 }
$db->getAttr(PDO::ATTR_CONNECTION_STATUS);

//PDO::ATTR_DRIVER_NAME { 返回当前 PDO 驱动名称 }
$db->getAttr(PDO::ATTR_DRIVER_NAME);

//PDO::ATTR_ERRMODE { 错误模式 }
//	0 = PDO::ERRMODE_SILENT { 仅设置错误代码 }
//	1 = PDO::ERRMODE_WARNING { 引发 E_WARNING 错误 }
//	2 = PDO::ERRMODE_EXCEPTION { 抛出 exceptions 异常 } { 默认值 }
$db->getAttr(PDO::ATTR_ERRMODE);

//PDO::ATTR_ORACLE_NULLS { 是否转换 NULL 和 空字符串 }
//	0 = PDO::NULL_NATURAL { 不转换 } { 默认值 }
//	1 = PDO::NULL_EMPTY_STRING { 强制将 空字符串 转换成 null }
//	2 = PDO::NULL_TO_STRING { 强制将 NULL 转换成 空字符串 }
$db->getAttr(PDO::ATTR_ORACLE_NULLS);

//PDO::ATTR_PERSISTENT {请求持久连接，而不是创建新连接}
// 	0 = false 	{ 关闭 } {默认值}
//	1 = true 	{ 开启 }
$db->getAttr(PDO::ATTR_PERSISTENT);

//PDO::ATTR_PREFETCH
//设置预取大小来为你的应用平衡速度和内存使用。
//较大的预取大小导致性能提高的同时也会占用更多的内存。
//注：并非所有的数据库/驱动组合都支持
$db->getAttr(PDO::ATTR_PREFETCH);

//PDO::ATTR_SERVER_INFO { 返回有关 PDO 连接到的数据库服务器的一些元信息 } { 只读属性 }
$db->getAttr(PDO::ATTR_SERVER_INFO);

//PDO::ATTR_SERVER_VERSION { 返回有关 PDO 连接到的数据库服务器版本的信息 } { 只读属性 }
$db->getAttr(PDO::ATTR_SERVER_VERSION);

//PDO::ATTR_TIMEOUT { 设置连接数据库的超时秒数 }
//注：并非所有的数据库/驱动组合都支持
$db->getAttr(PDO::ATTR_TIMEOUT);
```

### （5）、设置  `PDO`  当前连接属性的值

```php
//PDO::ATTR_AUTOCOMMIT { 是否自动提交每个单独的语句 }
//	1 = 自动提交 { 默认值 }
//	0 = 手动提交
$db->setAttr(PDO::ATTR_AUTOCOMMIT,0);
$db->setAttr(PDO::ATTR_AUTOCOMMIT,false);

//PDO::ATTR_CASE	{ 强制列名为指定的大小写 }
//	0 = PDO::CASE_NATURAL 	{ 保留数据库驱动返回的列名 } { 默认值 }
//	1 = PDO::CASE_UPPER 	{ 强制列名大写 }
//  2 = PDO::CASE_LOWER		{ 强制列名小写 }
$db->setAttr(PDO::ATTR_CASE,1);
$db->setAttr(PDO::ATTR_CASE,PDO::CASE_UPPER);

//PDO::ATTR_ERRMODE { 错误模式 }
//	0 = PDO::ERRMODE_SILENT { 仅设置错误代码 }
//	1 = PDO::ERRMODE_WARNING { 引发 E_WARNING 错误 }
//	2 = PDO::ERRMODE_EXCEPTION { 抛出 exceptions 异常 } { 默认值 }
$db->setAttr(PDO::ATTR_ERRMODE,1);
$db->setAttr(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);

//PDO::ATTR_ORACLE_NULLS { 是否转换 NULL 和 空字符串 }
//	0 = PDO::NULL_NATURAL { 不转换 } { 默认值 }
//	1 = PDO::NULL_EMPTY_STRING { 强制将 空字符串 转换成 null }
//	2 = PDO::NULL_TO_STRING { 强制将 NULL 转换成 空字符串 }
$db->setAttr(PDO::ATTR_ORACLE_NULLS,1);
$db->setAttr(PDO::ATTR_ORACLE_NULLS,PDO::NULL_EMPTY_STRING);

//PDO::ATTR_STRINGIFY_FETCHES { 强制将提取的所有值视为字符串 }
// 	0 = false 	{ 关闭 } {默认值}
//	1 = true 	{ 开启 }
$db->setAttr(PDO::ATTR_STRINGIFY_FETCHES,1);
$db->setAttr(PDO::ATTR_STRINGIFY_FETCHES,true);

//PDO::ATTR_TIMEOUT { 设置连接数据库的超时秒数 }
//注：并非所有的数据库/驱动组合都支持
$db->setAttr(PDO::ATTR_TIMEOUT,30);

//PDO::ATTR_EMULATE_PREPARES { 启用或禁用预处理语句的模拟 }
//有些驱动不支持或有限度地支持本地预处理。
//使用此设置强制PDO总是模拟预处理语句（如果为 true ），或试着使用本地预处理语句（如果为 false）。
//如果驱动不能成功预处理当前查询，它将总是回到模拟预处理语句上。需要 bool 类型。
$db->setAttr(PDO::ATTR_EMULATE_PREPARES, true);

//PDO::MYSQL_ATTR_USE_BUFFERED_QUERY { 使用缓冲查询 } { 在MySQL中可用 }
// 	0 = false 	{ 关闭 } {默认值}
//	1 = true 	{ 开启 }
$db->setAttr(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, 1);

//PDO::ATTR_DEFAULT_FETCH_MODE { 设置默认的提取模式 }
//	1 = PDO::FETCH_LAZY：结合使用 PDO::FETCH_BOTH 和 PDO::FETCH_OBJ，创建供用来访问的对象变量名 
//	2 = PDO::FETCH_ASSOC { 返回一个索引为结果集列名的数组 } 
//	3 = PDO::FETCH_NUM：返回一个索引为以0开始的结果集列号的数组 
//	4 = PDO::FETCH_BOTH { 返回一个索引为结果集列名和以0开始的列号的数组 } { 默认值 }
//	5 = PDO::FETCH_OBJ：返回一个属性名对应结果集列名的匿名对象 
//	6 = PDO::FETCH_BOUND { 返回 true ，并分配结果集中的列值给 PDOStatement::bindColumn() 方法绑定的 PHP 变量 } 
//	8 = PDO::FETCH_CLASS { 返回一个请求类的新实例，映射结果集中的列名到类中对应的属性名 }
//	{ 如果 fetch_style 包含 PDO::FETCH_CLASSTYPE（例如：PDO::FETCH_CLASS |PDO::FETCH_CLASSTYPE），则类名由第一列的值决定 }
//	9 = PDO::FETCH_INTO：更新一个被请求类已存在的实例，映射结果集中的列到类中命名的属性 
$db->setAttr(PDO::ATTR_DEFAULT_FETCH_MODE, 1);
$db->setAttr(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_LAZY);
```

### （6）、返回当前可用的 `PDO` 驱动

```php
$db->getDrivers();
```

### （7）、返回受影响的行数

```php
$db->rowCount()
```

### （8）、返回结果集中的总列数

```php
$db->columnCount();
```

### （9）、返回最后插入行的ID或序列值

```php
$db->lastInsertId();
```

### （10）、返回预处理的 `SQL`语句信息

```php
$db->sql();
```

# 七、开始使用

## 1、配置路由

在 `config/api.php` 中配置

> 语法：Api::请求方式( URL地址 , 控制器@方法 , 版本号 )

```php
<?php

namespace Frame;

/**
 *  V1 版本
 */

// GET 请求
Api::get('/users', 'Users@select', 'v1');    //查询所有用户数据
Api::get('/users/{id}', 'Users@select', 'v1');    //查询指定 ID 的用户数据
Api::get('/users/{id}/shop', 'Users@select', 'v1'); //查询指定 ID 的用户，所有购买的商品
Api::get('/users/{id}/shop/{id}', 'Users@select', 'v1');//查询指定 ID 的用户，购买的指定 ID 的商品


Api::get('/users?limit={limit}', 'Users@select', 'v1');    //指定返回记录的数量
Api::get('/users?offset={offset}', 'Users@select', 'v1');    //指定返回记录的开始位置
Api::get('/users?page={page}&per_page={per_page}', 'Users@select', 'v1');    //指定第几页，以及每页的记录数
Api::get('/users?sortby={sortby}&order={order}', 'Users@select', 'v1');    //指定返回结果按照哪个属性排序，以及排序顺序。

// POST 请求
Api::post('/users', 'Users@add', 'v1');    //新增用户数据

// PUT 请求
Api::put('/users', 'Users@edit', 'v1');    //更新用户数据

// DELETE 请求
Api::delete('/users/{id}', 'Users@delete', 'v1');    //删除指定 ID 的用户数据

/**
 *  V2 版本
 */
Api::get('/users', 'Users@select', 'v2');    //查询所有用户数据
```

## 2、编写 Api 接口文件

在  `Api/v1/`  目录下创建  `users.php`  文件，实现路由规则中定义的控制器与方法

```php
<?php

namespace Api\v1;

use Frame\Api;
use Frame\DB;

class Users
{
    public array $data = ['1', '2', '3'];

    public function select(): void
    {
        Api::success($this->data, '请求成功!');
    }

    public function insert(): void
    {
        Api::success($this->data, '请求成功!');
    }

    public function update(): void
    {
        Api::success($this->data, '请求成功!');
    }

    public function delete(): void
    {
        Api::success($this->data, '请求成功!');
    }
}
```
