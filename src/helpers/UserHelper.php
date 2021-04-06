<?php
/**
 * Created by
 * User: GuoZhaoXuan
 * Date: 2021/3/4
 * Time: 15:25
 */

namespace HyperfHttpAuth\helpers;


use Hyperf\Utils\Context;

class UserHelper
{

    /**
     * 获取用户容器对象
     *
     * @param null $coroutineId
     *
     * @return \App\Common\Auth\UserContainer|mixed|null
     */
    public static function getUserContainer($coroutineId = null)
    {
        return Context::get(UserContainer::class, new UserContainer(), $coroutineId);
    }

    /**
     * 设置容器
     *
     * @param \App\Common\Auth\UserContainer $userContainer
     *
     * @return mixed
     */
    public static function setUserContainer(UserContainer $userContainer)
    {
       return Context::set(UserContainer::class,$userContainer);
    }

}
