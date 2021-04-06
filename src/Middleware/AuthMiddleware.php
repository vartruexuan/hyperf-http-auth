<?php

declare(strict_types=1);

namespace HyperfHttpAuth\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Di\Annotation\AnnotationCollector;
use App\Data\ResponseData;
use HyperfHttpAuth\Auth\HttpHeaderAuth;
use HyperfHttpAuth\Annotation\FreeLogin;
use HyperfHttpAuth\UserContainer;
use HyperfHttpAuth\Helpers\Helper;
use HyperfHttpAuth\Helpers\UserHelper;

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
     * @var HttpResponse
     */
    protected $response;

    /**
     * @var ResponseData
     */
    protected $responseData;

    public function __construct(ContainerInterface $container, HttpResponse $response, ResponseData $responseData)
    {
        $this->container = $container;
        $this->response = $response;
        $this->responseData = $responseData;

    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $this->responseData->sendSuccess();
        $userContainer = new UserContainer();
        $userContainer->setUniqueId(BaseService::$project);
        $auth = $this->container->get(HttpHeaderAuth::class);
        // 得到路由
        [$controller, $action]=Helper::getControllerAction($request);

        //  FreeLogin 免登录
        if (!Helper::hasAnnotation(FreeLogin::class,$controller,$action)) {
            if (!$auth->authenticate($userContainer, $request, $response)) {

                // 待
            }
            // 设置对象
            UserHelper::setUserContainer($userContainer);
        }

        return $handler->handle($request);

    }
}
