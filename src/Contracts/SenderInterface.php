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

interface SenderInterface
{
    /**
     * Get the sender name.
     */
    public function getName(): string;

    /**
     * Send the message immediately.
     *
     * @throws DriverErrorException
     */
    public function send(SmsableInterface $smsable): array;
}
