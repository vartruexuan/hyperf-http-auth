<?php
/**
 * Created by
 * User: GuoZhaoXuan
 * Date: 2021/2/9
 * Time: 11:16
 */

namespace HyperfHttpAuth;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Str;
use Psr\Container\ContainerInterface;
use Hyperf\Redis\Redis;
use App\Common\Helpers\Helper;
use _HumbugBox2af02d339e80\phpDocumentor\Reflection\Types\Context;

class UserContainer
{

    private $uniqueId = 'project';
    private $identity;
    private $identityClass;
    private $accessToken;
    private $expire = 24 * 3600;

    /**
     * @Inject
     * @var  ContainerInterface
     */
    public $contain;

    public function __construct()
    {
        $this->setConfig(config($this->uniqueId . '.user', []));
    }

    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param \App\Common\Auth\IdentityInterface $identity
     *
     * @return $this
     */
    public function setIdentity(IdentityInterface $identity)
    {
        $this->identity = $identity;
        $this->identityClass = get_class($identity);
        return $this;
    }

    /**
     * 获取唯一值
     *
     * @return string
     */
    public function getUniqueId()
    {
        return $this->uniqueId;
    }

    /**
     * 设置唯一值
     *
     * @param $uniqueId
     *
     * @return $this
     */
    public function setUniqueId($uniqueId)
    {
        $this->uniqueId = $uniqueId;
        $this->setConfig(config($this->uniqueId . '.user', []));
        return $this;
    }

    /**
     * 获取id
     *
     * @return int|null
     */
    public function getId()
    {
        if ($this->identity) {
            return $this->identity->getId();
        }
        return null;
    }

    /**
     * 登录
     *
     * @param \App\Common\Auth\IdentityInterface $identity
     * @param int|null                           $expire
     *
     * @return bool
     */
    public function login(IdentityInterface $identity, ?int $expire = null)
    {
        $this->setIdentity($identity);
        $this->accessToken = $this->generateAccessToken();
        $tokenKey = $this->getAccessTokenKey($this->accessToken);
        $expire = intval($expire ?? $this->expire);
        return $this->getCache()->set($tokenKey, $identity->getId(), [
            'ex' => $expire,
        ]);
    }

    /**
     * 退出登录
     *
     * @param string|null $token
     *
     * @return bool
     */
    public function logout(?string $token = null)
    {
        $token = $token ? $token : $this->accessToken;
        if ($token) {
            $this->getCache()->del($this->getAccessTokenKey($token));
            return true;
        }
        return false;
    }

    /**
     * 登录 根据token
     *
     * @param $token
     *
     * @return bool
     */
    public function loginByAccessToken($token)
    {
        $this->accessToken=$token;
        if ($identity = $this->findIdentityByAccessToken($token)) {
            $this->setIdentity($identity);
            return $identity;
        }
        return null;
    }

    /**
     * 获取实列（根据ID）
     *
     * @param $id
     *
     * @return false|mixed
     */
    public function findIdentityById($id)
    {
        if (!$this->identityClass) {
            return false;
        }
        return call_user_func([$this->identityClass, 'findIdentityById'], $id);
    }

    /**
     * 获取实例(根据token)
     *
     * @param $token
     *
     * @return false|mixed
     */
    public function findIdentityByAccessToken($token)
    {
        $accessToken = $this->getAccessTokenKey($token);
        $id = $this->getCache()->get($accessToken);
        return $this->findIdentityById($id);
    }

    /**
     * 获取token key
     *
     * @param $token
     *
     * @return string
     */
    public function getAccessTokenKey($token)
    {
        return $this->uniqueId . ':auth:token:' . $token;
    }

    /**
     * 获取缓存对象
     *
     * @return \Hyperf\Redis\Redis
     */
    public function getCache(): Redis
    {
        return Helper::redis();
    }

    /**
     * 设置配置
     *
     * @param $config
     *
     * @return $this
     */
    public function setConfig($config)
    {
        if (is_array($config)) {
            foreach ($config as $key => $val) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $val;
                }
            }
        }
        return $this;
    }


    /**
     * 生成token
     *
     * @return string
     */
    public function generateAccessToken()
    {
        return Str::random(16);
    }

    /**
     * 获取当前token
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

}
