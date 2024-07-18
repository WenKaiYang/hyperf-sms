<?php

namespace Ella123\HyperfSms\Drivers;

use Ella123\HyperfSms\Contracts\SmsableInterface;
use Ella123\HyperfSms\Exceptions\DriverErrorException;
use function Hyperf\Support\class_basename;

/**
 * 啪嗖短信渠道
 * @see https://www.paasoo.cn/docs/sms-api
 */
class PaaSooDriver extends AbstractDriver
{

    public function send(SmsableInterface $smsable): array
    {
        $appKey = (string)$this->config->get('app_key');
        $secretKey = (string)$this->config->get('secret_key');

        $params = [
            'key' => $appKey,
            'secret' => $secretKey,
            'from' => $smsable->signature,
            'to' => $smsable->to,
            'text' => $smsable->content,
        ];

        $response = $this->client->get(
            url: 'https://api.paasoo.cn/json',
            query: $params,
        );

        $result = $response->toArray();

        if (($result['status'] ?? '-1') != 0) {
            throw new DriverErrorException(
                message: $result['status_code'] ?? 'PaaSoo send fail',
                code: (int)$result['status'],
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