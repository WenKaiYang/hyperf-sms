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
 * 英富必短信渠道.
 * @see https://www.infobip.com/docs/api/channels/sms/outbound-sms/send-sms-message
 */
class InfobipDriver extends AbstractDriver
{
    public function send(SmsableInterface $smsable): array
    {
        $apiUrl = (string)$this->config->get('api_url');
        $apiToken = (string)$this->config->get('api_token');

        $params = [
            "messages" => [[
                "destinations" => [[
                    "to" => $smsable->to,
                ]],
                "from" => $smsable->from ?: $smsable->signature,
                "text" => $smsable->content,
            ]]
        ];

        $response = $this->client->postJson(
            url: sprintf('https://%s/sms/2/text/advanced', $apiUrl),
            params: $params,
            headers: [
                'Authorization' => sprintf('App %s', $apiToken),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        );

        try {
            $result = $response->toArray();
            $params['content'] = $smsable->content;
            return [
                'result' => $result,
                'driver' => class_basename(__CLASS__),
                'message_id' => $result['messages']['messageId'] ?? '',
                'params' => $params,
            ];
        } catch (\Exception $exception) {

            throw new DriverErrorException(
                message: $exception->getMessage(),
                code: (int)$exception->getCode(),
                response: $response
            );
        }
    }

}
