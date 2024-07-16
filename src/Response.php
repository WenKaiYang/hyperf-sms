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

use Hyperf\Contract\Arrayable;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface, Arrayable
{
    public function __construct(public PsrResponseInterface $response)
    {
    }

    public function toArray(): array
    {
        $contentType = $this->response->getHeaderLine('Content-Type');
        $contents = $this->response->getBody()->getContents();

        if (stripos($contentType, 'json') !== false || stripos($contentType, 'javascript')) {
            return json_decode($contents, true);
        }

        if (stripos($contentType, 'xml') !== false) {
            return json_decode(json_encode(simplexml_load_string($contents)), true);
        }

        return [$contents];
    }

    public function getHeaderLine($name): string
    {
        return call_user_func_array([$this->response, __FUNCTION__], func_get_args());
    }

    public function getBody(): StreamInterface
    {
        return call_user_func_array([$this->response, __FUNCTION__], func_get_args());
    }

    public function getProtocolVersion(): string
    {
        return call_user_func_array([$this->response, __FUNCTION__], func_get_args());
    }

    public function withProtocolVersion($version): MessageInterface
    {
        return call_user_func_array([$this->response, __FUNCTION__], func_get_args());
    }

    public function getHeaders(): array
    {
        return call_user_func_array([$this->response, __FUNCTION__], func_get_args());
    }

    public function hasHeader($name): bool
    {
        return call_user_func_array([$this->response, __FUNCTION__], func_get_args());
    }

    public function getHeader($name): array
    {
        return call_user_func_array([$this->response, __FUNCTION__], func_get_args());
    }

    public function withHeader($name, $value): MessageInterface
    {
        return call_user_func_array([$this->response, __FUNCTION__], func_get_args());
    }

    public function withAddedHeader($name, $value): MessageInterface
    {
        return call_user_func_array([$this->response, __FUNCTION__], func_get_args());
    }

    public function withoutHeader($name): MessageInterface
    {
        return call_user_func_array([$this->response, __FUNCTION__], func_get_args());
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        return call_user_func_array([$this->response, __FUNCTION__], func_get_args());
    }

    public function getStatusCode(): int
    {
        return call_user_func_array([$this->response, __FUNCTION__], func_get_args());
    }

    public function withStatus($code, $reasonPhrase = ''): PsrResponseInterface
    {
        return call_user_func_array([$this->response, __FUNCTION__], func_get_args());
    }

    public function getReasonPhrase(): string
    {
        return call_user_func_array([$this->response, __FUNCTION__], func_get_args());
    }
}
