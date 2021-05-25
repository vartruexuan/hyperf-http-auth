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

class AuthHelper
{

    /**
     * 获取控制器|方法
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return false|string[]
     */
    public static function getControllerAction(ServerRequestInterface $request)
    {
        $route = $request->getAttribute(Dispatched::class)->handler->callback;
        if(is_string($route)){
            return explode('@', $route);
        }
        return $route;
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
    public static function hasAnnotation(string $annotation,string $class, string $method)
    {
        $classAnnotation = AnnotationCollector::getClassAnnotation($class, $annotation);
        $methodAnnotation = AnnotationCollector::getClassMethodAnnotation($class, $method);
        $methodAnnotation=$methodAnnotation ? array_keys($methodAnnotation) : [];
        return $classAnnotation || in_array($annotation,$methodAnnotation );
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
       return Context::set(UserContainer::class,$userContainer);
    }

}
