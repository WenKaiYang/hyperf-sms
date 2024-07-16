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

namespace Ella123\HyperfSms\Strategies;

use Ella123\HyperfSms\Concerns\HasSenderFilter;
use Ella123\HyperfSms\Contracts\StrategyInterface;

class OrderStrategy implements StrategyInterface
{
    use HasSenderFilter;

    public function apply(array $senders, string $number): array
    {
        return array_values($this->filterSenders($senders, $number));
    }
}
