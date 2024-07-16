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

use Ella123\HyperfSms\Drivers\AliyunDriver;
use Ella123\HyperfSms\Drivers\LogDriver;
use Ella123\HyperfSms\Strategies\OrderStrategy;

/**
 * This file is part of hyperf-ext/sms.
 *
 * @see     https://github.com/hyperf-ext/sms
 * @contact  eric@zhu.email
 * @license  https://github.com/hyperf-ext/sms/blob/master/LICENSE
 */
return [
    'timeout' => 5.0,

    'default' => [
        'strategy' => OrderStrategy::class,
        'senders' => ['aliyun', 'tencent_cloud'],
    ],

    'senders' => [
        'aliyun' => [
            'driver' => AliyunDriver::class,
            'config' => [
                'access_key_id' => '',
                'access_key_secret' => '',
                'sign_name' => '',
            ],
        ],

        'log' => [
            'driver' => LogDriver::class,
            'config' => [
                'name' => 'sms',
                'group' => 'default',
            ],
        ],
    ],

    'default_mobile_number_region' => null,
];
