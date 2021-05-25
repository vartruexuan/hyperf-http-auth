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
use FastRoute\Dispatcher;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Server\Exception\ServerException;
use Hyperf\Di\ReflectionManager;
use Hyperf\Validation\UnauthorizedException;

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

    protected $project = 'default';

    public function __construct(ContainerInterface $container, \Hyperf\HttpServer\Contract\ResponseInterface $response)
    {
        $this->container = $container;
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        $dispatched = $request->getAttribute(Dispatched::class);

        if (!$dispatched instanceof Dispatched) {
            throw new ServerException(sprintf('The dispatched object is not a %s object.', Dispatched::class));
        }

        if ($this->shouldHandle($dispatched)) {

            $this->authenticate($request);
        }

        return $handler->handle($request);
    }


    public function authenticate(ServerRequestInterface $request)
    {

        $userContainer = new UserContainer();
        $userContainer->setUniqueId($this->project);
        $auth = $this->container->get(HttpHeaderAuth::class);
        $dispatched = $request->getAttribute(Dispatched::class);
        [$requestHandler, $method] = $this->prepareHandler($dispatched->handler->callback);
        // annotation: FreeLogin
        if (!AuthHelper::hasAnnotation(FreeLogin::class, $requestHandler, $method)) {
            if (!$auth->authenticate($userContainer, $request, $this->response)) {
                throw new AuthenticationException('no authenticate ~');
            }
            // login user
            AuthHelper::setUserContainer($userContainer);
        }
    }


    /**
     * @param array|string $handler
     *
     * @see \Hyperf\HttpServer\CoreMiddleware::prepareHandler()
     */
    protected function prepareHandler($handler): array
    {
        if (is_string($handler)) {
            if (strpos($handler, '@') !== false) {
                return explode('@', $handler);
            }
            $array = explode('::', $handler);
            return [$array[0], $array[1] ?? null];
        }
        if (is_array($handler) && isset($handler[0], $handler[1])) {
            return $handler;
        }
        throw new \RuntimeException('Handler not exist.');
    }

    protected function shouldHandle(Dispatched $dispatched): bool
    {
        return $dispatched->status === Dispatcher::FOUND && !$dispatched->handler->callback instanceof Closure;
    }
}
