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
use Ella123\HyperfSms\Contracts\SmsableInterface;
use Ella123\HyperfSms\Contracts\SmsManagerInterface;
use Hyperf\Context\ApplicationContext;

class PendingSms
{
    /**
     * The "to" recipient of the message.
     */
    protected string $to;

    protected ?SmsManagerInterface $manger;

    protected ?SenderInterface $sender;

    public function __construct(SmsManagerInterface $manger)
    {
        $this->manger = $manger;
    }

    /**
     * Send a new SMS message instance immediately.
     */
    public function sendNow(SmsableInterface $smsable): array
    {
        return $this->manger->sendNow($this->fill($smsable));
    }

    /**
     * Set the recipients of the message.
     */
    public function to(int|string $number): static
    {
        $this->to = (string) $number;

        return $this;
    }

    /**
     * Set the sender of the SMS message.
     */
    public function sender(string $name): static
    {
        $this->sender = ApplicationContext::getContainer()->get(SmsManagerInterface::class)->get($name);

        return $this;
    }

    /**
     * Send a new SMS message instance.
     */
    public function send(SmsableInterface $smsable): array|bool
    {
        return $this->manger->send($this->fill($smsable));
    }

    /**
     * Push the given SMS message onto the queue.
     */
    public function queue(SmsableInterface $smsable, ?string $queue = null): bool
    {
        return $this->manger->queue($this->fill($smsable), $queue);
    }

    /**
     * Deliver the queued SMS message after the given delay.
     */
    public function later(SmsableInterface $smsable, int $delay, ?string $queue = null): bool
    {
        return $this->manger->later($this->fill($smsable), $delay, $queue);
    }

    /**
     * Populate the SMS message with the addresses.
     */
    protected function fill(SmsableInterface $smsable): SmsableInterface
    {
        $smsable->to($this->to);
        if ($this->sender) {
            $smsable->sender($this->sender->getName());
        }
        return $smsable;
    }
}
