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

namespace Ella123\HyperfSms\Drivers;

use Ella123\HyperfSms\Contracts\SmsableInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LogDriver extends AbstractDriver
{
    protected LoggerInterface $logger;

    public function __construct(ContainerInterface $container, array $config)
    {
        parent::__construct($config);

        $this->logger = $container->get(LoggerFactory::class)->get(
            name: $config['name'] ?? 'sms',
            group: $config['group'] ?? 'default'
        );
    }

    public function send(SmsableInterface $smsable): array
    {
        $log = sprintf(
            "To: %s | From: \"%s\" | Content: \"%s\" | Signature: \"%s\" | Data: %s\n",
            $smsable->to,
            $smsable->from,
            $smsable->content,
            $smsable->signature,
            json_encode($smsable->data)
        );

        $this->logger->info($log);

        return [
            'result' => [
                'to' => $smsable->to,
                'from' => $smsable->from,
                'content' => $smsable->content,
                'signature' => $smsable->signature,
                'data' => $smsable->data,
            ],
            'driver' => 'log',
        ];
    }
}
