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
class UserHelper
{

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
