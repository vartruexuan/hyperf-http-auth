<?php
/**
 * Created by
 * User: GuoZhaoXuan
 * Date: 2021/1/4
 * Time: 10:35
 */
declare(strict_types=1);

namespace HyperfHttpAuth\Helpers;


use Hyperf\Utils\ApplicationContext;
use Hyperf\HttpServer\Router\Dispatched;
use Psr\Http\Message\ServerRequestInterface;
use Hyperf\Redis\RedisFactory;
use Hyperf\Redis\Redis;
use Hyperf\Di\Annotation\AnnotationCollector;
use App\Annotation\FreeLogin;

class Helper
{


    /**
     * 验证数组是否含有指定成员
     *
     * @param array $haveArray 指定成员
     * @param array $fromArray 寻找数组
     * @param bool  $isAnd     true:必须含有所有成员， false 含有一个及以上成员
     *
     * @return bool
     */
    public static function haveArray(array $haveArray, array $fromArray, $isAnd = true)
    {
        $haveCount = count($haveArray);
        $intersect = array_intersect($haveArray, $fromArray);

        if ($isAnd) {
            return count($intersect) == $haveCount;
        } else {
            return count($intersect) > 0;
        }
    }

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
        return explode('@', $route);
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
        $classAnnotation = AnnotationCollector::getClassAnnotation($class, FreeLogin::class);
        $methodAnnotation = AnnotationCollector::getClassMethodAnnotation($class, $method);
        $methodAnnotation=$methodAnnotation ? array_keys($methodAnnotation) : [];
        return $classAnnotation || in_array($annotation,$methodAnnotation );
    }

    /**
     * 返回redis对象
     *
     * @param string $poolName
     *
     * @return \Hyperf\Redis\Redis
     */
    public static function redis($poolName = 'default'): Redis
    {
        return ApplicationContext::getContainer()->get(RedisFactory::class)->get($poolName);
    }

}
