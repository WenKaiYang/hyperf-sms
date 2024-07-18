<?php

namespace Ella123\HyperfSms\Drivers;

use Ella123\HyperfSms\Contracts\SmsableInterface;
use Ella123\HyperfSms\Exceptions\DriverErrorException;
use function Hyperf\Support\class_basename;

/**
 * 颂量短信渠道
 * @see https://www.itniotech.cn/api/sms/sendSms/
 */
class ItniotechDriver extends AbstractDriver
{

    public function send(SmsableInterface $smsable): array
    {
        $timestamp = time();
        $appKey = (string)$this->config->get('app_key');
        $secretKey = (string)$this->config->get('secret_key');
        $appId = (string)$this->config->get('app_id');

        $params = [
            'appId' => $appId,
            'numbers' => $smsable->to,
            'content' => $smsable->content,
            'senderId' => $smsable->signature,
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
                message: $result['reason'] ?? 'Itniotech send fail',
                code: (int)$result['status'],
                response: $response
            );
        }

        return [
            'result' => $result,
            'driver' => strtolower(class_basename(__CLASS__)),
            'message_id' => $result['array'][0]['msgId'] ?? '',
            'params' => $params,
        ];
    }
}