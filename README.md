# Zerg

## thinkPHP

### MVC 架构

1. 业务由专用的 model 层负责，通过业务层返回实体对象到 controller
2. controller 把实体对象组装成 view 之后返回给客户端

### 文件目录

* app: 应用目录
* config: 配置目录
* view: 视图目录
* route: 路由定制文件
* public: web 目录，包含入口文件，所有文件都能被访问到
* extend: 扩展类库目录
* runtime: 应用的运行时目录，包含日志和缓存文件
* vendor: Composor 类库目录
* think: 命令行入口文件
* .example.env: 环境变量示例文件
* composer.json: composer 定义文件

### URL 路径格式

> path info 格式

* http://servername/index.php/module/controller/action/[param/value...]
* index 是 ThinkPHP 默认的保留字，即使省略也会被补全
* url 不区分大小写
* 兼容模式会把 module 和 module 后的内容当成一个 search`http://servername/index.php?s=/module/controller/action/[param/value...]`
* 缺点
    1. 太长
    2. URL 路径暴露了服务器文件结构
    3. 不够灵活，不能支持 URL 语义化
  

> 路由模式

多应用需要在自己的应用文件夹下新建一个 route 文件夹，之后在文件夹中创建 php 文件定义路由

可以自己设置路由路径名，设置之后就不能通过 path info 访问被替换的路由了

```php
// 注册路由到News控制器的read操作
Route::rule('new/:id','News/read');

// 第二个参数是三段式，第一个是模块名，第二个是控制器名，第三个是方法名
// 如果中间有一段有多层文件夹，则需要通过 . 连接
// 比如下面的文件结构是 News/controller/read/article.php -> getText方法
Route::rule('new/:id', 'News/read.article/getText');

// 可以在rule方法中指定请求类型（不指定的话默认为任何请求类型有效），例如：
Route::rule('new/:id', 'News/update', 'POST');

// 或者直接使用方法
Route::get('new/:cate', 'News/category');


```

> 只使用路由模式

进入 config/route.php 设置 url_route_must 为 true

> 可以设置虚拟域名，在开发时代替冗长的url路径

* 编辑 xampp/apache/conf/extra/httpd-vhosts.conf 文件，加上

```apacheconf
<VirtualHost *:8080>
    DocumentRoot "E:\project\xampp\htdocs\Zerg\public"
    ServerName z.cn
</VirtualHost>
```

这样是让 apache 认出 z.cn 是干嘛的

* 如果想 apache 进入 localhost:8080 时依然访问到我们的项目，可以编辑 httpd-vhosts.conf 文件，加上

```apacheconf
<VirtualHost *:8080>
    DocumentRoot "E:\project\xampp\htdocs"
    ServerName localHost
</VirtualHost>
```

* 编辑 C:\windows\System32\drivers\etc，加上:

```hosts
127.0.0.1 z.cn
```

* 重启 apache

### 多模块

* TP 下所有的模块的类都要以 app/模块名/controller 作为命名空间，其中 app 是根命名空间。想要在创建类时自动补上命名空间，可以进入设置 -> 文件夹 -> 选择 app 设定它为源文件夹，它的 package name 设为 app

* 模块其实挺大的，不要一个功能一个模块

## PHPStorm 调试

* 在 public 目录下可以新建一个 info.php，之后通过浏览器访问 public/info.php 就能获取网站 php 的大概情况

```php
phpinfo();
```

进入生产环境时一定要把 info.php 删除

* 进入 info.php 之后，可以将其源代码复制进 xdebug 网页中进行分析然后下载对应版本并按提示一步一步操作
* 在 php.ini 的最后加入以下内容

```txt
[Xdebug]
zend_extension=xdebug
xdebug.remote_enable=1
xdebug.remote_handler=dbgp
xdebug.remote_mode=req
xdebug.remote_host=localhost
xdebug.remote_port=9000
xdebug.idekey="PHPSTORM"
```

之后重启 xampp

* 进入 PHPStorm 在编辑器的上方点击添加配置，之后进行新增配置，选择`PHP web 页面`，起始 URL 填写要编辑的页面，之后就能打断点调试

## 路由

* Route::rule('路由表达式', '路由地址', '请求类型', '路由参数（数组）', '变量规则（数组）')
* 路由数据获取
  

```PHP
  // 引入 Request 并获取数据
  class Test {
    // 直接通过参数获取
    public function hello($id, $name, $age) {}
  }
  
  // 引入 Request，通过 Request 获取 
  use think\facade\Request;
  
  class Test1 {
    // 直接通过参数获取
    public function hello() {
      // 实例化后通过 param 名获取值，如果不传字符串则获取所有 param 的数组
      Request::instance() -> param('id'); 
      // 通过 get 可以获取 get 请求的 query
      Request::instance() -> get(); 
      // 获取路径
      Request::instance() -> route(); 
      // 通过 input 助手函数获取
      $all = input('get.age');
    }
  }
  ```

## validate

validate 接收一个数组，里面有对每个值的验证要求

> 独立验证

```php
class test {
  public function getBanner($id) {
    $data = [
      'name' => 'vendor',
      'email' => 'vendor@qq.com'
    ];
    
    $validate = new Validate([
      'name' => 'require|max:10',
      'email' => 'email'
    ]);
    
    // batch 可以进行批量验证，这样能输出几个错误信息 
    $result = $validate -> batch() -> check($data);
    echo $validate -> getError();
  }
}
```

> 验证器

和独立验证的区别是对验证规则做了更好的封装

```php
use think\Validate;

class TestValidate extends Validate {
  protected $rule = [
    'name' => 'require|max: 10',
    'email' => 'email'
  ];
}
```

## REST

REST(Representational State Transfer): 表述性状态转移，一种风格，约束或是设计理念
SOAP(Simple Object Access Protocol): 使用 XML 表述数据

> Restful api 是 web 接口中的一种应用和延伸

RESTFul API 基于 REST 的 API 设计理念

### 特点
- 轻
- 通常来说，使用 JSON 描述数据
- 无状态。
  - 表示发送两个 HTTP 请求，则两个请求没有关联，没有先后顺序 
  - 有关联就是比如操作数据库，需要先连接数据库再对数据库进行操作
- 基于资源，增删查改都只是对于资源状态的改变
- 使用 HTTP 动词来操作资源，而 URL 里应该是名词，而不是动词

### 最佳实践
方法：
- POST: 创建
- PUT: 更新
- GET: 查询
- DELETE: 删除
状态码：404、400、200、201、202、401、403、500
错误码：自定义的错误ID号
统一描述错误：错误码、错误信息、当前URL
  
使用 Token 令牌来授权和验证身份
版本控制
测试与生产环境分开：api.xxx.com, dev.api.xxx.com

URL 语义要明确，最好是有一份比较标准的文档

## 异常

想要覆写异常类，自动抛出指定的错误类，需要到对应应用下的 provider.php 文件下绑定异常处理类

```php
<?php

use app\lib\exception\ExceptionHandler;
use app\Request;

// 容器Provider定义文件
return [
  'think\Request'          => Request::class,
  'think\exception\Handle' => ExceptionHandler::class,
];
```

### 异常分类
- 由于用户行为导致的异常（没有通过验证器，没查询到结果）。通常不需要记录日志，需要向用户返回具体信息
- 服务器自身异常（代码错误，调用外部接口日志）。通常记录日志，不想客户端返回具体原因

/think/Exception 和 /think/HttpException 并没有联系，两边都是继承 Exception

### 日志

Thinkphp6 会自动把错误记录到日志，需要先关闭，日志的配置文件是配置文件目录下的log.php文件，系统在进行日志写入之前会读取该配置文件进行初始化。

- 日志不要输出到项目中
- 返回给客户端的错误 json 确实可以让客户端简单调试，但是服务端不好调试，服务端最好使用自带的错误处理，即在浏览器上显示错误详情和调用栈。所以我们需要判断是否在开发阶段，在调试时返回错误页面，上线时返回 json 结构体。

### 如何学习
- 模仿
  - 豆瓣开放 API
  - GITHUB 开发者 API
  
切勿盲目照搬标准 REST

### AOP
面向切面编程，写代码时候要站在更高的高度来抽象代码。像错误处理，middler 都是AOP。

## 产品功能

* 购物车保存在微信本地，如果想保存在数据库，则需要使用 websocket，优点是
  + 多端可以共享购物车数据
  + 服务器可以通过购物车分析用户数据

## SQL

* 不使用外键约束，如果想快速迭代产品，可以考虑不使用外键约束
* 使用假删除，不会物理删除数据。系统稳定性更高，更容易分析用户数据，此时外键约束就不好用了
* 设计数据库需要时间积累，需要一边写代码一边改，倾向于迭代方式

通过 config/database.php 配置数据库

查询数据库的方法分为三种
- 原生 sql 语句
- 使用 query 查询器
- 使用模型以及关联模型

如果想 sql 输出日志，则需要以下几步
- config/database.php 中的 trigger_sql 设置为 true
- 记得日志级别为 sql
- sql 日志能够记录每次查询的速度，可以用于性能调优以及导向错误。在生产环境中不需要开启，在有需要的时候再开启

### 数据库对象
- Db: 数据库操作的入口对象，如果是查询，更新，操作数据都是通过这个对象。同时用来连接数据库。可以兼容不同的数据库操作。
- Collection: Db 通过这个对象来连接数据库，通过连接器里的 TBO 来连接。它平时是待命状态，当真正执行语句时才会连接数据库，所以这个连接是惰性的。
- Query: 查询器。它是对数据库常见操作 CURD 的一种封装，它可以很优雅地来编写 sql 语句，并支持链式调用。但实际上也是对 sql 语句的封装
- Builder: 生成器。对 query 的语句翻译成不同类型的数据库的 sql 语句，也是通过 Collection 连接数据库并操作数据库
- Drivers: 驱动。Db 会根据不同的数据库配置选择不同的驱动

> 使用中间层，比如 Db 类就能统一适配并操作数据库

### query 语句
- find 返回一条数据库记录，select 返回一个所有满足条件的数据库记录，只有最后调用 find 或 select 之后才会生成 sql 语句。相应的方法还有 delete 和 insert

- where 可三个参数：字段名，表达式，查询条件。也可接收一个匿名函数，接受一个query对象，它能进行链式操作。
- fetchSql 查看当前 query 语句输出的 sql 语句而不执行。

### ORM 与模型
ORM(Object Relation Mapping) 对象关系映射，使用表时将它定义为一个对象，用来操作对象。表与表之间的关系就是对象和对象之间的作用。

在ThinkPHP中，模型就是 ORM 的具体实现，它还会包含一些业务逻辑，是一个业务的集合。是用来处理一些复杂的业务逻辑。和表并没有必然联系，是和业务逻辑有关的。

在数据库里有主表和从表的概念，而关联模型就是把这种关系映射到 model 中。

默认情况下，模型类名和表名是一一对应的，不需要特别设置，如果名字不同，则需要在 Model 类中定义 $table 属性来指定对应的表

Model 使用静态调用有以下几个好处
- 更简洁
- 数据库的单位是表，以及表里的记录。而模型类对应的就是一张表，当实例化后对应的就是一条记录
如果实例化后再去获取相当于找到一条记录再去找一条记录，因此应该使用静态调用来获取
  
获取方法
- get: 返回一条记录，模型特有方法。
- find: 返回一条记录，DB特有方法。
- all: 返回一组记录，模型特有方法。
- select: 返回一组记录，DB特有方法。

推荐使用模型来操作数据，而不是使用 Db 层，尽管会消耗一些性能。好的代码的第一好处是可读性好，绝大多数的产品对性能没有要求。如果产品访问很慢，实际上并不是 ORM 引起的，ORM 的性能损耗并不大，用户并不能感知出来，应该关注的是如何写 sql 语句。

