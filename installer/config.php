<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'packages' => [
        'hyperf/framework' => [
            'version' => '2.0.*',
        ],
        'hyperf/di' => [
            'version' => '2.0.*',
        ],
    ],
    'require-dev' => [
    ],
    'questions' => [
        'framework' => [
            'question' => 'Do you want to use hyperf/framework component ?',
            'default' => 'n',
            'required' => false,
            'force' => true,
            'custom-package' => true,
            'options' => [
                1 => [
                    'name' => 'yes',
                    'packages' => [
                        'hyperf/framework',
                    ],
                ],
            ],
        ],
        'di' => [
            'question' => 'Do you want to use hyperf/di component ?',
            'default' => 'n',
            'required' => false,
            'force' => true,
            'custom-package' => true,
            'options' => [
                1 => [
                    'name' => 'yes',
                    'packages' => [
                        'hyperf/di',
                    ],
                ],
            ],
        ],
    ],
];
