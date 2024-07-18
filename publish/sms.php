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
use Ella123\HyperfSms\Drivers\ItniotechDriver;
use Ella123\HyperfSms\Drivers\LogDriver;
use Ella123\HyperfSms\Drivers\NXCloudDriver;
use Ella123\HyperfSms\Drivers\PaaSooDriver;
use Ella123\HyperfSms\Strategies\OrderStrategy;
use function Hyperf\Support\env;

return [
    'timeout' => 5.0,

    'default' => [
        'strategy' => OrderStrategy::class,
        'senders' => ['log', 'aliyun'],
    ],

    'senders' => [
        'aliyun' => [
            'driver' => AliyunDriver::class,
            'config' => [
                'access_key_id' => env('SMS_ALIYUN_ACCESS_KEY_ID'),
                'access_key_secret' => env('SMS_ALIYUN_ACCESS_KEY_SECRET'),
                'sign_name' => env('SMS_ALIYUN_SIGN_NAME'),
            ],
        ],
        'log' => [
            'driver' => LogDriver::class,
            'config' => [
                'name' => env('SMS_LOG_NAME', 'sms'),
                'group' => env('SMS_LOG_GROUP', 'default'),
            ],
        ],
        'itniotech' => [
            'driver' => ItniotechDriver::class,
            'config' => [
                'app_key' => env('SMS_ITNIOTECH_APP_KEY'),
                'secret_key' => env('SMS_ITNIOTECH_SECRET_KEY'),
                'app_id' => env('SMS_ITNIOTECH_APP_ID'),
            ],
        ],
        'nxcloud' => [
            'driver' => NXCloudDriver::class,
            'config' => [
                'app_key' => env('SMS_NXCLOUD_APP_KEY'),
                'secret_key' => env('SMS_NXCLOUD_SECRET_KEY'),
            ],
        ],
        'paasoo' => [
            'driver' => PaaSooDriver::class,
            'config' => [
                'app_key' => env('SMS_PAASOO_APP_KEY'),
                'secret_key' => env('SMS_PAASOO_SECRET_KEY'),
            ],
        ],
    ],

    'default_mobile_number_region' => null,
];
