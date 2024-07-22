# 短信扩展 hyperf-sms

```shell
composer require ella123/hyperf-sms
```

# 发布配置

```shell
php bin/hyperf.php vendor:publish ella123/hyperf-sms
```

# 使用案例

```php
# 同步发送
\Ella123\HyperfSms\Sms::to()->sendNow(smsable: new DomeSms())
# 同步发送（或队列）
\Ella123\HyperfSms\Sms::to()->send(smsable: new DomeSms())
# 队列发送
\Ella123\HyperfSms\Sms::to()->queue(smsable: new DomeSms(),queue: 'default')
# 队列延迟发送
\Ella123\HyperfSms\Sms::to()->later(smsable: new DomeSms(),delay: 3,queue: 'default')
```

# 配置介绍

```php
// 阿里短信渠道
'aliyun' => [
    'driver' => AliyunDriver::class,
    'config' => [
        'access_key_id' => env('SMS_ALIYUN_ACCESS_KEY_ID'),
        'access_key_secret' => env('SMS_ALIYUN_ACCESS_KEY_SECRET'),
        'sign_name' => env('SMS_ALIYUN_SIGN_NAME'),
    ],
],
// 系统日志
'log' => [
    'driver' => LogDriver::class,
    'config' => [
        'name' => env('SMS_LOG_NAME', 'sms'),
        'group' => env('SMS_LOG_GROUP', 'default'),
    ],
],
// 颂量短信渠道
'itniotech' => [
    'driver' => ItniotechDriver::class,
    'config' => [
        'app_key' => env('SMS_ITNIOTECH_APP_KEY'),
        'secret_key' => env('SMS_ITNIOTECH_SECRET_KEY'),
        'app_id' => env('SMS_ITNIOTECH_APP_ID'),
    ],
],
// 牛信短信渠道
'nxcloud' => [
    'driver' => NXCloudDriver::class,
    'config' => [
        'app_key' => env('SMS_NXCLOUD_APP_KEY'),
        'secret_key' => env('SMS_NXCLOUD_SECRET_KEY'),
    ],
],
// 啪嗖短信渠道
'paasoo' => [
    'driver' => PaaSooDriver::class,
    'config' => [
        'app_key' => env('SMS_PAASOO_APP_KEY'),
        'secret_key' => env('SMS_PAASOO_SECRET_KEY'),
    ],
],
```

