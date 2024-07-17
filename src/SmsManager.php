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

use Ella123\HyperfSms\Contracts\SenderInterface;
use Ella123\HyperfSms\Contracts\ShouldQueue;
use Ella123\HyperfSms\Contracts\SmsableInterface;
use Ella123\HyperfSms\Contracts\SmsManagerInterface;
use Ella123\HyperfSms\Exceptions\StrategicallySendMessageException;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Logger\LoggerFactory;
use InvalidArgumentException;
use LogicException;
use Psr\Container\ContainerInterface;
use Throwable;
use function Hyperf\Support\make;

class SmsManager implements SmsManagerInterface
{
    /**
     * The container instance.
     */
    protected ContainerInterface $container;

    /**
     * The array of resolved senders.
     *
     * @var SenderInterface[]
     */
    protected array $senders = [];

    /**
     * The config instance.
     *
     * @var array
     */
    protected mixed $config;

    /**
     * Create a new Mail manager instance.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->config = $container->get(ConfigInterface::class)->get('sms');
    }

    public function get(string $name): SenderInterface
    {
        if (empty($this->senders[$name])) {
            $this->senders[$name] = $this->resolve($name);
        }

        return $this->senders[$name];
    }

    public function sendNow(SmsableInterface $smsable): array
    {
        $senders = empty($smsable->sender) ? $this->applyStrategy($smsable) : [$smsable->sender];

        $exception = null;

        foreach ($senders as $sender) {
            try {
                return $smsable->send($this->get($sender));
            } catch (Throwable $throwable) {
                $this->container->get(LoggerFactory::class)
                    ->get(
                        name: $this->config['senders']['log']['config']['name'] ?? 'sms',
                        group: $this->config['senders']['log']['config']['group'] ?? 'default'
                    )
                    ->error(sprintf(
                        '%s[%s] in %s%s%s',
                        $throwable->getMessage(),
                        $throwable->getLine(),
                        $throwable->getFile(),
                        PHP_EOL,
                        $throwable->getTraceAsString()
                    ));

                $exception = empty($exception)
                    ? new StrategicallySendMessageException('The SMS manger encountered some errors on strategically send the message', $throwable)
                    : $exception->appendStack($exception);
            }
        }

        throw $exception;
    }

    public function send(SmsableInterface $smsable)
    {
        if ($smsable instanceof ShouldQueue) {
            return $smsable->queue();
        }

        return $this->sendNow($smsable);
    }

    public function queue(SmsableInterface $smsable, ?string $queue = null): bool
    {
        return $smsable->queue($queue);
    }

    public function later(SmsableInterface $smsable, int $delay, ?string $queue = null): bool
    {
        return $smsable->later($delay, $queue);
    }

    public function to(int|string $number): PendingSms
    {
        return (new PendingSms($this))->to((string)$number);
    }

    /**
     * Resolve the given sender.
     */
    protected function resolve(string $name): SenderInterface
    {
        $config = $this->config['senders'][$name] ?? null;

        if (is_null($config)) {
            throw new InvalidArgumentException("The SMS sender [{$name}] is not defined.");
        }

        return make(Sender::class, compact('name', 'config'));
    }

    protected function applyStrategy(SmsableInterface $smsable): array
    {
        $senders = (is_array($smsable->senders) && count($smsable->senders) > 0)
            ? $smsable->senders
            : (
            is_array($this->config['default']['senders'])
                ? $this->config['default']['senders']
                : [$this->config['default']['senders']]
            );

        if (empty($senders)) {
            throw new LogicException('The SMS senders value is missing on SmsMessage class or default config');
        }

        $strategy = $smsable->strategy ?: $this->config['default']['strategy'];

        if (empty($strategy)) {
            throw new LogicException('The SMS strategy value is missing on SmsMessage class or default config');
        }

        return make($strategy)->apply($senders, $smsable->to);
    }
}
