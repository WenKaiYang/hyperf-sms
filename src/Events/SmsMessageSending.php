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

namespace Ella123\HyperfSms\Events;

use Ella123\HyperfSms\Contracts\SmsableInterface;

class SmsMessageSending
{
    public function __construct(public SmsableInterface $smsable)
    {
    }
}
