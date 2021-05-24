<?php
/**
 * Created by
 * User: GuoZhaoXuan
 * Date: 2021/5/24
 * Time: 11:52
 */

namespace Vartruexuan\HyperfHttpAuth\Store;
use Hyperf\Redis\Redis;


class RedisDriver implements DriverInterface
{

    /**
     * @var Redis null
     */
    public static  $instance=null;

    public function __construct()
    {
        // 初始化redis对象


    }

    public function get(string $key)
    {
        // TODO: Implement get() method.
    }

    public function set(string $key, string $value, ?int $expire = null)
    {
        // TODO: Implement set() method.
    }

    public function del(string $key): bool
    {
        // TODO: Implement del() method.
    }
}
