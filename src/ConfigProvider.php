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

use Ella123\HyperfSms\Commands\GenSmsCommand;
use Ella123\HyperfSms\Contracts\SmsManagerInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                SmsManagerInterface::class => SmsManager::class,
            ],
            'commands' => [
                GenSmsCommand::class,
            ],
            'listeners' => [
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for ella123/hyperf-sms.',
                    'source' => __DIR__ . '/../publish/sms.php',
                    'destination' => BASE_PATH . '/config/autoload/sms.php',
                ],
            ],
        ];
    }
}
