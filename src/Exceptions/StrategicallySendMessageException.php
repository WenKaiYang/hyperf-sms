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

use RuntimeException;
use Throwable;

class StrategicallySendMessageException extends RuntimeException
{
    protected array $stack = [];

    public function __construct($message, Throwable $throwable)
    {
        parent::__construct($message, 0);

        $this->appendStack($throwable);
    }

    public function appendStack(Throwable $throwable): void
    {
        $this->stack[] = $throwable;
    }

    /**
     * @return Throwable[]
     */
    public function getStack(): array
    {
        return $this->stack;
    }
}
