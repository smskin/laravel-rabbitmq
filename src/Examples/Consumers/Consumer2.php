<?php

namespace SMSkin\LaravelRabbitMq\Examples\Consumers;

use PhpAmqpLib\Message\AMQPMessage;
use SMSkin\LaravelRabbitMq\Entities\Consumer;
use SMSkin\LaravelRabbitMq\Examples\Queues\Queue2;

class Consumer2 extends Consumer
{
    public function getQueue(): string
    {
        return (new Queue2())->getName();
    }

    public function handleMessage(AMQPMessage $message): void
    {
        echo "\n--------\n";
        echo static::class . ': ' . $message->body;
        echo "\n--------\n";

        $message->ack();
    }
}
