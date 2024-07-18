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

use function Hyperf\Support\class_basename;

/**
 * 颂量短信渠道.
 * @see https://www.itniotech.cn/api/sms/sendSms/
 */
class ItniotechDriver extends AbstractDriver
{
    public function send(SmsableInterface $smsable): array
    {
        $timestamp = time();
        $appKey = (string) $this->config->get('app_key');
        $secretKey = (string) $this->config->get('secret_key');
        $appId = (string) $this->config->get('app_id');

        $params = [
            'appId' => $appId,
            'numbers' => $smsable->to,
            'content' => $smsable->content,
            'senderId' => $smsable->from ?: $smsable->signature,
        ];

        $response = $this->client->postJson(
            endpoint: 'https://api.itniotech.com/sms/sendSms',
            params: $params,
            headers: [
                'Content-Type' => 'application/json;charset=UTF-8',
                'Sign' => md5($appKey . $secretKey . $timestamp),
                'Timestamp' => $timestamp,
                'Api-Key' => $appKey,
            ]
        );

        $result = $response->toArray();

        if (($result['status'] ?? '-1') != 0) {
            throw new DriverErrorException(
                message: $result['reason'] ?: $this->getMessage($result['status']),
                code: (int) $result['status'],
                response: $response
            );
        }

        return [
            'result' => $result,
            'driver' => class_basename(__CLASS__),
            'message_id' => $result['array'][0]['msgId'] ?? '',
            'params' => $params,
        ];
    }

    protected function getMessage($status): string
    {
        return match ((int) $status) {
            -1 => 'Authentication error',
            -2 => 'Restricted IP access',
            -3 => 'Sensitive characters in SMS content',
            -4 => 'SMS content is empty',
            -5 => 'The SMS content is too long',
            -6 => 'SMS that is not a template',
            -7 => 'Phone number exceeds limit',
            -8 => 'The phone number is empty',
            -9 => 'Abnormal phone number',
            -10 => "The customer's balance is insufficient",
            -13 => 'User locked',
            -16 => 'Timestamp expires',
            -18 => 'port program unusual',
            -19 => 'Please contact the business managers binding channel',
            default => 'Itniotech send fail',
        };
    }
}
