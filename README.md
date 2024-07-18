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