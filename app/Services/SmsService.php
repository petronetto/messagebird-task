<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\InvalidArgumentException;
use Core\Config\ConfigInterface;
use MessageBird\Client;
use MessageBird\Objects\Message;

class SmsService
{
    /** @var Client */
    protected $client;

    /** @var ConfigInterface */
    protected $config;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param  string $originator
     * @param  array  $recipients
     * @param  string $text
     * @return mixed
     */
    public function send(string $originator, array $recipients, string $text)
    {
        $message             = new Message;
        $message->originator = $originator;
        $message->recipients = $recipients;

        $text = $this->stringToHexa($text);

        if (strlen($text) >= 160) {
            return $this->sendBinarySms($message, $text);
        }

        return $this->sendSms($message, $text);
    }

    /**
     * @param  Message $message
     * @param  string  $text
     * @return mixed
     */
    protected function sendSms(Message $message, string $text): Message
    {
        $client = $this->getClient();

        $ref = rand(1, 255);

        $message->setBinarySms(
            $this->getUdh($ref, 1, 1),
            $text
        );

        return $client->messages->create($message);
    }

    /**
     * @param  Message $message
     * @param  string  $text
     * @return array
     */
    protected function sendBinarySms(Message $message, string $text): array
    {
        $response = [];

        $parts = $this->splitLongMessages($text);

        $ref = rand(1, 255);

        foreach ($parts as $index => $part) {
            $client = $this->getClient();
            sleep(1);

            $message->setBinarySms(
                $this->getUdh($ref, count($parts), $index + 1),
                $part
            );

            $response[] = $client->messages->create($message);
        }

        return $response;
    }

    /**
     * @return Client
     */
    protected function getClient(): Client
    {
        if (!$key = $this->config->get('sms.key')) {
            throw new InvalidArgumentException('You must provide the API Key');
        }

        return new Client($key);
    }

    /**
     * @param  int    $id
     * @param  int    $total
     * @param  int    $current
     * @return string
     */
    protected function getUdh(int $id, int $total, int $current): string
    {
        $toHex = function (int $value) {
            return substr('0' . dechex($value), -2);
        };

        $id      = $toHex($id);
        $total   = $toHex($total);
        $current = $toHex($current);

        return sprintf('050003%s%s%s', $id, $total, $current);
    }

    /**
     * @param  string $message
     * @return array
     */
    protected function splitLongMessages(string $message): array
    {
        return str_split($message, 160);
    }

    /**
     * @param  string $string
     * @return string
     */
    protected function stringToHexa(string $string): string
    {
        $hex   = null;
        $chars = str_split($string);

        foreach ($chars as $char) {
            $ord     = ord($char);
            $hexCode = dechex($ord);
            $hex .= substr('0' . $hexCode, -2);
        }

        return strToUpper($hex);
    }
}
