<?php

declare(strict_types=1);

namespace App\Jobs;

use Core\Jobs\JobInterface;
use MessageBird\Client;
use MessageBird\Objects\Message;

class SendSms implements JobInterface
{
    /** @var Client */
    private $client;

    /** @var Message */
    private $message;

    /**
     * @param Client  $client
     * @param Message $message
     */
    public function __construct(Client $client, Message $message)
    {
        $this->client  = $client;
        $this->message = $message;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $result = $this->client->messages->create($this->message);
    }
}
