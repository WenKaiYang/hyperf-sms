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

use Ella123\HyperfSms\Client;
use Ella123\HyperfSms\Contracts\DriverInterface;
use Hyperf\Config\Config;

abstract class AbstractDriver implements DriverInterface
{
    protected Client $client;

    protected Config $config;

    /**
     * The driver constructor.
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
        $this->client = new Client($this->getClientOptions());
    }

    /**
     * Return base Guzzle options.
     */
    protected function getClientOptions(): array
    {
        $options = method_exists($this, 'getGuzzleOptions') ? $this->getGuzzleOptions() : [];

        return array_merge($this->config->get('guzzle', []), $options, [
            'base_uri' => method_exists($this, 'getBaseUri') ? $this->getBaseUri() : '',
            'timeout' => method_exists($this, 'getTimeout') ? $this->getTimeout() : 5.0,
        ]);
    }
}
