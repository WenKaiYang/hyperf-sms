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
 * 钉钉消息渠道.
 * @see https://open.dingtalk.com/document/orgapp/custom-robots-send-group-messages
 */
class DingTalkDriver extends AbstractDriver
{
    public function send(SmsableInterface $smsable): array
    {
        $accessToken = (string)$this->config->get('access_token');
        $secretKey = (string)$this->config->get('secret_key');
        // 添加签名
        $timestamp = round(microtime(true) * 1000);
        $sign = $this->generateSignature($secretKey, $timestamp);

        $params = [
            'msgtype' => 'text',
            'text' => [
                'content' => $smsable->content,
            ],
            'at' => [
                'isAtAll' => 'false',
                'atMobiles' => [$smsable->to],
            ],
        ];

        $response = $this->client->postJson(
            url: sprintf(
                'https://oapi.dingtalk.com/robot/send?access_token=%s&timestamp=%s&sign=%s',
                $accessToken,
                $timestamp,
                $sign
            ),
            params: $params,
        );

        $result = $response->toArray();

        if (($result['errcode'] ?? '-1') != 0) {
            throw new DriverErrorException(
                message: $this->getErrorMessage($result),
                code: (int)$result['errcode'],
                response: $response
            );
        }

        $params['content'] = $smsable->content;
        return [
            'result' => $result,
            'driver' => class_basename(__CLASS__),
            'message_id' => '',
            'params' => $params,
        ];
    }

    protected function generateSignature(string $secret, float $timestamp): string
    {
        $stringToSign = $timestamp . "\n" . $secret;
        $hash = hash_hmac('sha256', $stringToSign, $secret, true);
        $sign = base64_encode($hash);
        return urlencode($sign);
    }

    protected function getErrorMessage(array $result): string
    {
        return match (intval($result['errcode'])) {
            -1 => '系统繁忙',
            40035 => '缺少参数 json',
            43004 => '无效的HTTP HEADER Content-Type',
            400013 => '群已被解散',
            400101 => 'access_token不存在',
            400102 => '机器人已停用',
            400105 => '不支持的消息类型',
            400106 => '机器人不存在',
            410100 => '发送速度太快而限流',
            430101 => '含有不安全的外链',
            430102 => '含有不合适的文本',
            430103 => '含有不合适的图片',
            430104 => '含有不合适的内容',
            310000 => '安全设置错误码',
            default => $result['errmsg'] ?? '',
        };
    }
}
