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

use Ella123\HyperfSms\Exceptions\DriverErrorException;

/**
 * @property string[] $senders
 * @property string $strategy
 * @property null|string $from
 * @property string $to
 * @property null|string $content
 * @property null|string $template
 * @property null|string $signature
 * @property array $data
 */
interface SmsableInterface
{
    /**
     * Set the SMS message sender number.
     *
     * @return $this
     */
    public function from(string $from);

    /**
     * Set the SMS message recipient number.
     *
     * @return $this
     */
    public function to(string $to);

    /**
     * Set the SMS message content.
     *
     * @return $this
     */
    public function content(string $content);

    /**
     * Set the SMS message template.
     *
     * @return $this
     */
    public function template(string $template);

    /**
     * Set the SMS message signature.
     *
     * @return $this
     */
    public function signature(string $signature);

    /**
     * Set the SMS message data.
     *
     * @param array|string $key
     * @param null|mixed $value
     *
     * @return $this
     */
    public function with($key, $value = null);

    /**
     * Set the strategy.
     *
     * @return $this
     */
    public function strategy(string $class);

    /**
     * Set the list of sender name of the SMS message.
     *
     * @param string[] $names
     *
     * @return $this
     */
    public function senders(array $names);

    /**
     * Set the sender name of the SMS message. This will override `$senders` property value.
     *
     * @return $this
     */
    public function sender(string $name);

    /**
     * Send the SMS message immediately.
     *
     * @throws DriverErrorException
     */
    public function send(?SenderInterface $sender = null): array;

    /**
     * Queue the SMS message for sending.
     */
    public function queue(?string $queue = null): bool;

    /**
     * Deliver the queued SMS message after the given delay.
     */
    public function later(int $delay, ?string $queue = null): bool;
}
