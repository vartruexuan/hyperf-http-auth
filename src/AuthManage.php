<?php
/**
 * Created by
 * User: GuoZhaoXuan
 * Date: 2021/5/25
 * Time: 14:16
 */

namespace Vartruexuan\HyperfHttpAuth;


use Vartruexuan\HyperfHttpAuth\Auth\AuthInterface;
use Vartruexuan\HyperfHttpAuth\Auth\HttpHeaderAuth;

class AuthManage
{
    const CONFIG_NAME = 'hyperf_http_auth';

    /**
     * 获取配置
     *
     * @param null $key
     *
     * @return mixed
     */
    public function getConfig($key = null)
    {
        return config(self::CONFIG_NAME . ($key ? ".{$key}" : ''));
    }

    /**
     * auth token
     *
     * @param string $uniqueId
     *
     * @return mixed|string|\Vartruexuan\HyperfHttpAuth\Auth\AuthInterface|null
     */
    public function getAuthClass($uniqueId = 'default')
    {
        $config = $this->getConfig($uniqueId);
        $authClass = $config['authClass'] ?? null;
        if (!class_exists($authClass) || !in_array(AuthInterface::class, class_implements($authClass))) {
            $authClass = HttpHeaderAuth::class;
        }
        return $authClass;
    }
}
