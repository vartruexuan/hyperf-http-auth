<?php
/**
 * Created by
 * User: GuoZhaoXuan
 * Date: 2021/5/24
 * Time: 11:53
 */

namespace Vartruexuan\HyperfHttpAuth\Store;


class MemcacheDriver implements DriverInterface
{

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
