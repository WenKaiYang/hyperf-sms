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
use Hyperf\AsyncQueue\Job;

class QueuedSmsableJob extends Job
{
    public function __construct(public SmsableInterface $smsable)
    {
    }

    public function handle(): void
    {
        $this->smsable->send();
    }
}
