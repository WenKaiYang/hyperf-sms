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

use Ella123\HyperfSms\Exceptions\RequestException;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\ApplicationContext;

class Client
{
    protected \GuzzleHttp\Client $client;

    public function __construct(array $config = [])
    {
        $this->client = ApplicationContext::getContainer()->get(ClientFactory::class)->create($config);
    }

    /**
     * Make a get request.
     *
     * @throws RequestException
     */
    public function get(string $url, array $query = [], array $headers = []): Response
    {
        return $this->request('get', $url, [
            'headers' => $headers,
            'query' => $query,
        ]);
    }

    /**
     * Make a http request.
     */
    public function request(string $method, string $endpoint, array $options = []): Response
    {
        try {
            return new Response($this->client->{$method}($endpoint, $options));
        } catch (GuzzleRequestException $e) {
            throw new RequestException(
                $e->getMessage(),
                $e->getRequest(),
                new Response($e->getResponse()),
                $e->getPrevious(),
                $e->getHandlerContext()
            );
        }
    }

    /**
     * Make a post request.
     *
     * @throws RequestException
     */
    public function post(string $url, array $params = [], array $headers = []): Response
    {
        return $this->request('post', $url, [
            'headers' => $headers,
            'form_params' => $params,
        ]);
    }

    /**
     * Make a post request with json params.
     *
     * @throws RequestException
     */
    public function postJson(string $url, array $params = [], array $headers = []): Response
    {
        return $this->request('post', $url, [
            'headers' => $headers,
            'json' => $params,
        ]);
    }
}
