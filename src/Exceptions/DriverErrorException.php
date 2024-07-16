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

namespace Ella123\HyperfSms\Exceptions;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;

class DriverErrorException extends RuntimeException
{
    public ?ResponseInterface $response;

    public function __construct(string $message, $code = null, ?ResponseInterface $response = null, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->response = $response;
    }

    final public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
