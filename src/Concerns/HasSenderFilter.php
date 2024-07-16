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

namespace Ella123\HyperfSms\Concerns;

trait HasSenderFilter
{
    protected function filterSenders(array $senders, string $number): array
    {
        // 地区
        $region = '';
        $output = [];
        foreach ($senders as $key => $value) {
            if (is_array($value)) {
                if (in_array($region, array_map('strtolower', $value))) {
                    $output[] = $key;
                }
            } else {
                $output[] = $value;
            }
        }
        return $output;
    }
}
