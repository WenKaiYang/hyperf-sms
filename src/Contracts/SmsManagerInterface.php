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

namespace Ella123\HyperfSms\Contracts;

use Ella123\HyperfSms\Exceptions\StrategicallySendMessageException;

interface SmsManagerInterface
{
    /**
     * Send the given message immediately.
     *
     * @throws StrategicallySendMessageException
     */
    public function sendNow(SmsableInterface $smsable): array;

    /**
     * Send the given message.
     *
     * @return array|bool
     */
    public function send(SmsableInterface $smsable);

    /**
     * Queue the message for sending.
     */
    public function queue(SmsableInterface $smsable, ?string $queue = null): bool;

    /**
     * Deliver the queued message after the given delay.
     */
    public function later(SmsableInterface $smsable, int $delay, ?string $queue = null): bool;
}
