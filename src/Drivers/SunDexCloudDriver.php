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

use Ella123\HyperfSms\Contracts\SmsableInterface;
use Ella123\HyperfSms\Exceptions\DriverErrorException;

/**
 * 讯达云短信渠道.
 * @see https://showdoc.vnandcn.com/web/#/p/5a162670101deb502589c0a55c9c3fc8
 */
class SunDexCloudDriver extends AbstractDriver
{
    public function send(SmsableInterface $smsable): array
    {
        $appKey = (string)$this->config->get('app_key');
        $secretKey = (string)$this->config->get('secret_key');
        $timestamp = (string)floor(microtime(true) * 1000);
        $params = [
            'appId' => $appKey,
            'appSecret' => $secretKey,
            'timestamp' => $timestamp,
        ];
        $sign = md5(http_build_query($params));


        $response = $this->client->postJson(
            url: 'https://api.sundexcloudsms.com/api/v1/messages/send',
            params: [
                'appId' => $appKey,
                'content' => $smsable->content,
                'mobiles' => $smsable->to,
            ],
            headers: [
                'sign' => $sign,
                'timestamp' => $timestamp,
            ]
        );

        $result = $response->toArray();

        if ((int)($result['code'] ?? '-1') !== 200) {
            throw new DriverErrorException(
                message: $result['message'] ?? 'SunDexCloud send fail',
                code: (int)$result['code'],
                response: $response
            );
        }

        return [
            'result' => $result,
            'driver' => class_basename(__CLASS__),
            'message_id' => $result['data'] ?? '',
            'params' => $params + [
                    'content' => $smsable->content,
                    'mobiles' => $smsable->to,
                    'sign' => $sign,
                ],
        ];
    }
}
