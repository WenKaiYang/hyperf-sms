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

namespace Ella123\HyperfSms\Jobs;

use Ella123\HyperfSms\Contracts\SmsableInterface;
use Ella123\HyperfSms\Exceptions\StrategicallySendMessageException;
use Exception;
use Hyperf\AsyncQueue\Job;
use Hyperf\Context\ApplicationContext;
use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class QueuedSmsableJob extends Job
{
    public function __construct(public SmsableInterface $smsable)
    {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(): void
    {
        try {
            $this->smsable->send();
        } catch (Exception $exception) {
            $logger = ApplicationContext::getContainer()->get(LoggerFactory::class)->get('sms');
            if ($exception instanceof StrategicallySendMessageException) {
                foreach ($exception->getStack() as $e) {
                    $message = sprintf(
                        '%s[%s] in %s%s%s',
                        $e->getMessage(),
                        $e->getLine(),
                        $e->getFile(),
                        PHP_EOL,
                        $e->getTraceAsString()
                    );
                    $logger->error($message);
                }
            } else {
                $message = sprintf(
                    '%s[%s] in %s%s%s',
                    $exception->getMessage(),
                    $exception->getLine(),
                    $exception->getFile(),
                    PHP_EOL,
                    $exception->getTraceAsString()
                );
                $logger->error($message);
            }
            throw $exception;
        }
    }
}
