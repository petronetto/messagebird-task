<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\SendSms;
use Core\Config\ConfigInterface;
use Core\Exceptions\InvalidArgumentException;
use Core\Jobs\Dispatcher;
use MessageBird\Client;
use MessageBird\Objects\BaseList;
use MessageBird\Objects\Message;

class SmsService
{
    /** @var Client */
    protected $client;

    /** @var ConfigInterface */
    protected $config;

    /**
     * @param  ConfigInterface          $config
     * @param  Client                   $client
     * @throws InvalidArgumentException
     */
    public function __construct(ConfigInterface $config, Client $client)
    {
        $this->config = $config;
        $this->client = $client;

        if (!$key = $this->config->get('sms.key')) {
            throw new InvalidArgumentException('You must provide the API Key');
        }

        $this->client->setAccessKey($key);
    }

    /**
     * @param  array    $parameters
     * @return BaseList
     */
    public function getList(array $parameters = []): BaseList
    {
        return $this->client->messages->getList($parameters);
    }

    /**
     * @param  string   $id
     * @return BaseList
     */
    public function read($id)
    {
        return $this->client->messages->read($id);
    }

    /**
     * @param string $originator
     * @param array  $recipients
     * @param string $text
     */
    public function send(string $originator, array $recipients, string $text)
    {
        $message             = new Message;
        $message->originator = $originator;
        $message->recipients = $recipients;

        if (strlen($text) >= 160) {
            $text = $this->stringToHexa($text);

            $this->sendBinarySms($message, $text);

            return;
        }

        $this->sendSms($message, $text);
    }

    /**
     * @param Message $message
     * @param string  $text
     */
    protected function sendSms(Message $message, string $text)
    {
        $message->body = $text;

        $dispatcher = new Dispatcher;

        $job = new SendSms($this->client, $message);

        $dispatcher->dispatch($job);
    }

    /**
     * @param Message $message
     * @param string  $text
     */
    protected function sendBinarySms(Message $message, string $text)
    {
        $response = [];

        $parts = $this->splitLongMessages($text);

        $ref = rand(1, 255);

        foreach ($parts as $index => $part) {
            $udh = $this->getUdh($ref, count($parts), $index + 1);

            $message->setBinarySms($udh, $part);

            $dispatcher = new Dispatcher;

            $job = new SendSms($this->client, $message);

            $dispatcher->dispatch($job);
        }
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
