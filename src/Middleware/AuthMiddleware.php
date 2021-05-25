<?php

declare(strict_types=1);

namespace Vartruexuan\HyperfHttpAuth\Middleware;


use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Vartruexuan\HyperfHttpAuth\Auth\HttpHeaderAuth;
use Vartruexuan\HyperfHttpAuth\Annotation\FreeLogin;
use Vartruexuan\HyperfHttpAuth\User\UserContainer;
use Vartruexuan\HyperfHttpAuth\Helpers\AuthHelper;
use Vartruexuan\HyperfHttpAuth\Exception\AuthenticationException;

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

    public $response;

    protected $project='default';

    public function __construct(ContainerInterface $container, \Hyperf\HttpServer\Contract\ResponseInterface $response )
    {
        $this->container = $container;
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        $this->authenticate($request);
        return $handler->handle($request);
    }


    public function authenticate(ServerRequestInterface $request){

        $userContainer = new UserContainer();
        $userContainer->setUniqueId($this->project);
        $auth = $this->container->get(HttpHeaderAuth::class);
        [$controller, $action]=AuthHelper::getControllerAction($request);
        // annotation: FreeLogin
        if (!AuthHelper::hasAnnotation(FreeLogin::class,$controller,$action)) {
            if (!$auth->authenticate($userContainer, $request, $this->response)) {
                throw new AuthenticationException('no authenticate ~');
            }
            // login user
            AuthHelper::setUserContainer($userContainer);
        }
    }
}
