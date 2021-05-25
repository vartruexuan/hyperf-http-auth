<?php
/**
 * Created by
 * User: GuoZhaoXuan
 * Date: 2021/3/4
 * Time: 15:25
 */

namespace Vartruexuan\HyperfHttpAuth\Helpers;


use Hyperf\Utils\Context;
use Vartruexuan\HyperfHttpAuth\User\UserContainer;
use Psr\Http\Message\ServerRequestInterface;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\HttpServer\Router\Dispatched;
use FastRoute\Dispatcher;

class AuthHelper
{

    /**
     * get Controller action.
     *
     * @param array|string $handler
     */
    protected function prepareHandler($handler): array
    {
        // $request->getAttribute(Dispatched::class)->handler->callback
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


    /**
     * 方法是否含有对应注解
     *
     * @param string $annotation 注解class
     * @param string $class
     * @param string $method
     *
     * @return bool
     */
    public static function hasAnnotation(string $annotation, string $class, string $method)
    {
        $classAnnotation = AnnotationCollector::getClassAnnotation($class, $annotation);
        $methodAnnotation = AnnotationCollector::getClassMethodAnnotation($class, $method);
        $methodAnnotation = $methodAnnotation ? array_keys($methodAnnotation) : [];
        return $classAnnotation || in_array($annotation, $methodAnnotation);
    }


    /**
     * 获取用户容器对象
     *
     * @param null $coroutineId
     *
     * @return UserContainer|mixed|null
     */
    public static function getUserContainer($coroutineId = null)
    {
        return Context::get(UserContainer::class, new UserContainer(), $coroutineId);
    }

    /**
     * 设置容器
     *
     * @param UserContainer $userContainer
     *
     * @return mixed
     */
    public static function setUserContainer(UserContainer $userContainer)
    {
        return Context::set(UserContainer::class, $userContainer);
    }

}
