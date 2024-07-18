<?php

namespace Ella123\HyperfSms\Drivers;

use Ella123\HyperfSms\Contracts\SmsableInterface;
use Ella123\HyperfSms\Exceptions\DriverErrorException;
use function Hyperf\Support\class_basename;

/**
 * 牛信短信渠道
 * @see https://www.nxcloud.com/document/sms/mt-sending
 */
class NXCloudDriver extends AbstractDriver
{

    public function send(SmsableInterface $smsable): array
    {
        $appKey = (string)$this->config->get('app_key');
        $secretKey = (string)$this->config->get('secret_key');

        $params = [
            'appkey' => $appKey,
            'secretkey' => $secretKey,
            'phone' => $smsable->to,
            'content' => $smsable->content,
            'source_address' => $smsable->signature,
        ];

        $response = $this->client->post(
            url: 'http://api2.nxcloud.com/api/sms/mtsend',
            params: $params,
            headers: [
                'Content-Type' => ' application/x-www-form-urlencoded'
            ]
        );

        $result = $response->toArray();

        if (($result['code'] ?? '-1') != 0) {
            throw new DriverErrorException(
                message: $result['result'] ?? 'NXCloud send fail',
                code: (int)$result['code'],
                response: $response
            );
        }

        return [
            'result' => $result,
            'driver' => strtolower(class_basename(__CLASS__)),
            'message_id' => $result['messageid'] ?? '',
            'params' => $params,
        ];
    }
}