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

namespace Ella123\HyperfSms;

use Ella123\HyperfSms\Contracts\SmsableInterface;
use Ella123\HyperfSms\Contracts\SmsManagerInterface;
use Hyperf\Context\ApplicationContext;
use function Hyperf\Support\make;

/**
 * @method static PendingSms to(string $number)
 * @method static PendingSms send(SmsableInterface $smsable)
 * @method static PendingSms sendNow(SmsableInterface $smsable)
 * @method static PendingSms queue(SmsableInterface $smsable, ?string $queue = null)
 * @method static PendingSms later(SmsableInterface $smsable, int $delay, ?string $queue = null)
 */
class Sms
{
    public static function __callStatic(string $method, array $args)
    {
        $instance = static::getManager();

        return $instance->{$method}(...$args);
    }

    public static function sender(string $name)
    {
        return (new PendingSms(static::getManager()))->sender($name);
    }

    protected static function getManager()
    {
        if (!ApplicationContext::getContainer()->has(SmsManagerInterface::class)) {
            ApplicationContext::getContainer()->set(SmsManagerInterface::class, make(SmsManagerInterface::class));
        }

        return ApplicationContext::getContainer()->get(SmsManagerInterface::class);
    }
}
