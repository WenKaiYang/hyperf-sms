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

use Ella123\HyperfSms\Contracts\DriverInterface;
use Ella123\HyperfSms\Contracts\SenderInterface;
use Ella123\HyperfSms\Contracts\SmsableInterface;
use Ella123\HyperfSms\Events\SmsMessageSending;
use Ella123\HyperfSms\Events\SmsMessageSent;
use Hyperf\Macroable\Macroable;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use function Hyperf\Support\make;

class Sender implements SenderInterface
{
    use Macroable;

    protected string $name;

    protected DriverInterface $driver;

    protected ContainerInterface $container;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        string             $name,
        array              $config,
        ContainerInterface $container
    )
    {
        $this->name = $name;
        $this->driver = make($config['driver'], ['config' => $config['config'] ?? []]);
        $this->eventDispatcher = $container->get(EventDispatcherInterface::class);
        $this->container = $container;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function send(SmsableInterface $smsable): array
    {
        $smsable = clone $smsable;

        call_user_func([$smsable, 'build'], $this);

        $this->eventDispatcher->dispatch(new SmsMessageSending($smsable));

        $result = $this->driver->send($smsable);

        $this->eventDispatcher->dispatch(new SmsMessageSent($smsable, $result));

        return $result;
    }
}
