<?php
/**
 * Created by
 * User: GuoZhaoXuan
 * Date: 2021/5/24
 * Time: 11:50
 */

namespace Vartruexuan\HyperfHttpAuth\Store;


interface DriverInterface
{
    public function get(string $key);

    public function set(string $key,string $value,?int $expire=null);

    public function del(string $key):bool;
}
