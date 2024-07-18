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

class RandomStrategy implements StrategyInterface
{
    use HasSenderFilter;

    public function apply(array $senders, ?string $number = null): array
    {
        $senders = $this->filterSenders($senders, $number);

        uasort($senders, function () {
            return mt_rand() - mt_rand();
        });

        return array_values($senders);
    }
}
