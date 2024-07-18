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
use Ella123\HyperfSms\Exceptions\DriverErrorException;
use Error;
use Exception;
use Psr\Http\Message\ResponseInterface;

class SmsMessageSendFail
{
    public ?ResponseInterface $response = null;

    public function __construct(public SmsableInterface $smsable, public Error|Exception $exception)
    {
        if ($this->exception instanceof DriverErrorException) {
            $this->response = $this->exception->response;
        }
    }
}
