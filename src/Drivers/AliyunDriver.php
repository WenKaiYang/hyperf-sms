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
 * 阿里短信渠道
 * @see https://help.aliyun.com/zh/sms/developer-reference/api-dysmsapi-2017-05-25-sendsms?spm=a2c4g.101414.0.i0
 */
class AliyunDriver extends AbstractDriver
{
    protected const ENDPOINT_URL = 'https://dysmsapi.aliyuncs.com';

    protected const ENDPOINT_METHOD = 'SendSms';

    protected const ENDPOINT_VERSION = '2017-05-25';

    protected const ENDPOINT_FORMAT = 'JSON';

    protected const ENDPOINT_SIGNATURE_METHOD = 'HMAC-SHA1';

    protected const ENDPOINT_SIGNATURE_VERSION = '1.0';

    public function send(SmsableInterface $smsable): array
    {
        $data = $smsable->data;

        $signName = $smsable->signature ?: $this->config->get('sign_name');

        $params = [
            'AccessKeyId' => $this->config->get('access_key_id'),
            'Format' => self::ENDPOINT_FORMAT,
            'SignatureMethod' => self::ENDPOINT_SIGNATURE_METHOD,
            'SignatureVersion' => self::ENDPOINT_SIGNATURE_VERSION,
            'SignatureNonce' => uniqid(),
            'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'Action' => self::ENDPOINT_METHOD,
            'Version' => self::ENDPOINT_VERSION,
            'PhoneNumbers' => $smsable->to,
            'SignName' => $signName,
            'TemplateCode' => $smsable->template,
            'TemplateParam' => json_encode($data, JSON_FORCE_OBJECT),
        ];

        $params['Signature'] = $this->generateSign($params);

        $response = $this->client->get(self::ENDPOINT_URL, $params);

        $result = $response->toArray();

        if ($result['Code'] != 'OK') {
            throw new DriverErrorException(
                message: $result['Message'] ?? 'Aliyun send fail',
                code: (int)$result['Code'],
                response: $response
            );
        }

        return [
            'result' => $result,
            'driver' => strtolower(class_basename(__CLASS__)),
            'message_id' => $result['BizId'] ?? '',
            'params' => $params,
        ];
    }

    protected function generateSign(array $params): string
    {
        ksort($params);
        $accessKeySecret = $this->config->get('access_key_secret');
        $stringToSign = 'GET&%2F&' . urlencode(http_build_query($params, '', '&', PHP_QUERY_RFC3986));

        return base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . '&', true));
    }
}
