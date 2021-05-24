<?php

declare(strict_types=1);

namespace Vartruexuan\HyperfHttpAuth\Middleware;

use Psr\Container\ContainerInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Vartruexuan\HyperfHttpAuth\Auth\HttpHeaderAuth;
use Vartruexuan\HyperfHttpAuth\Annotation\FreeLogin;
use Vartruexuan\HyperfHttpAuth\UserContainer;
use Vartruexuan\HyperfHttpAuth\Helpers\Helper;
use Vartruexuan\HyperfHttpAuth\Helpers\UserHelper;

/**
 * 用户权限验证
 *
 * Class AuthMiddleware
 *
 * @package App\Module\backend\Middleware
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;



    /**
     * @Inject
     * @var ResponseInterface
     */
    public $response;

    /**
     * @var ResponseData
     */
    protected $responseData;

    public function __construct(ContainerInterface $container, ResponseInterface $response )
    {
        $this->container = $container;
        $this->response = $response;

    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $userContainer = new UserContainer();
        $userContainer->setUniqueId(BaseService::$project);
        $auth = $this->container->get(HttpHeaderAuth::class);
        [$controller, $action]=Helper::getControllerAction($request);
        // annotation: FreeLogin
        if (!Helper::hasAnnotation(FreeLogin::class,$controller,$action)) {
            if (!$auth->authenticate($userContainer, $request, $this->response)) {
                throw new AuthenticationException();
            }
            // login user
            UserHelper::setUserContainer($userContainer);
        }
        return $handler->handle($request);

    }
}
