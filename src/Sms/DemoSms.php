<?php

namespace Ella123\HyperfSms\Sms;

use Ella123\HyperfSms\Contracts\SenderInterface;
use Ella123\HyperfSms\Contracts\ShouldQueue;
use Ella123\HyperfSms\Smsable;

class DemoSms extends Smsable implements ShouldQueue
{
    /**
     * Create a new SMS message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the SMS message.
     */
    public function build(SenderInterface $sender): void
    {
        $this->content('demo');
    }
}