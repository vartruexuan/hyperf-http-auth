<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'default' => [
        'driver' => 'redis', // 存储数据驱动  redis memcache
        'redis' => [
            'pool' => 'default',
        ],
        // 用户权限配置
        "user"=>[
            'identityClass'=>'App\Module\backend\Model\User', // 用户model
            'expire'=>24*3600,
        ],
    ],
];
