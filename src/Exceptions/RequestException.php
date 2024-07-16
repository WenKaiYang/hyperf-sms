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

use Ella123\HyperfSms\Response;

/**
 * @method Response getResponse()
 */
class RequestException extends \GuzzleHttp\Exception\RequestException
{
}
