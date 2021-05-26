# hyperf-http-auth

### 安装

```bash
composer require vartruexuan/hyperf-http-auth
```

### 发布配置文件

```bash
php bin/hyperf.php vendor:publish vartruexuan/hyperf-http-auth
```

### 创建 User model 文件 并且实现接口 IdentityInterface

```php
<?php

namespace App\Model;

use Vartruexuan\HyperfHttpAuth\User\IdentityInterface;

class User implements IdentityInterface
{
    /**
     * 获取用户对象
     *
     * @param $id
     *
     * @return \App\Model\User
     */
    public static function findIdentityById($id)
    {
        // TODO: Implement findIdentityById() method.
        return User::query()->find($id);
    }

    /**
     *
     * @return mixed
     */
    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->id;
    }

}

```

### 修改配置文件

```php

<?php

declare(strict_types=1);

return [
    'default' => [
        // 用户权限配置
        "user"=>[
            'identityClass'=>'App\Model\User', // 指定用户model	
	// 目前只支持 HttpHeaderAuth 您也可以自己重构，实现Vartruexuan\HyperfHttpAuth\Auth\AuthInterface 这里指定您自己的类即可
            'authClass'=>\Vartruexuan\HyperfHttpAuth\Auth\HttpHeaderAuth::class, // 默认HttpHeaderAuth
            'expire'=>24*3600, // 过期时长
        ],
    ]
];

```



### 设置中间件 （这里展示配置方式/路由方式，具体可参考 [hyperf](https://hyperf.wiki/2.1/#/zh-cn/middleware/middleware) 官方文档）
1.配置方式 config/autoload/middlewares.php
```php
<?php

declare(strict_types=1);

return [
    'http' => [
        Vartruexuan\HyperfHttpAuth\Middleware\AuthMiddleware::class, // 登录权限
    ],
];

```
2.路由方式
```php

<?php

use Hyperf\HttpServer\Router\Router;

// backend
router::addGroup('/backend/', function () {
    // Index
    router::addGroup('index/', function () {
        Router::addRoute(['GET', 'OPTIONS'], 'index', 'App\Module\backend\Controller\IndexController@index');
        // 登录
        Router::addRoute(['POST', 'OPTIONS'], 'login', 'App\Module\backend\Controller\IndexController@login');
        // 退出登录
        Router::addRoute(['POST', 'OPTIONS'], 'logout', 'App\Module\backend\Controller\IndexController@logout');
        Router::addRoute(['POST', 'OPTIONS'], 'test', 'App\Module\backend\Controller\IndexController@test');
    });


}, [
    'middleware' => [
        // 登录权限验证
      Vartruexuan\HyperfHttpAuth\Middleware\AuthMiddleware::class,

    ]
]);

```
### 使用

```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use Vartruexuan\HyperfHttpAuth\Annotation\FreeLogin;

/**
 * Class IndexController
 *
 * @package App\Controller
 */
class IndexController extends BaseController
{

    /**
     * @Inject
     * @var \App\Service\Uc\UserService
     */
    public $userService;

    /**
     * 登录
     *
     * @FreeLogin
     */
    public function login()
    {
        $username = $this->request->post('username', '');
        $password = $this->request->post('password', '');
        $identity=User::validatePassword($username,$password);
        // 授权登录信息
        $userContainer = AuthHelper::getUserContainer();
        $userContainer->login($identity);

        return $this->sendSuccess([
            'access_token' => $userContainer->getAccessToken()
        ]);
    }

    /**
     * 退出登录
     *
     */
    public function logout()
    {
        AuthHelper::getUserContainer()->logout();
        return $this->sendSuccess();
    }

    public function info()
    {
        // 获取当前用户对象
        $user= AuthHelper::getUserContainer()->getIdentity();

    }



}

```

### 注解 免登录 FreeLogin, 目前只支持注解方式（后期加上配置方式）

```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use Vartruexuan\HyperfHttpAuth\Annotation\FreeLogin;

/**
 * Class IndexController
 * @Freelogin
 * @package App\Controller
 */
class IndexController extends BaseController
{

    /**
     * 退出登录
     * @Freelogin
     */
    public function list()
    {

    }
}

```
