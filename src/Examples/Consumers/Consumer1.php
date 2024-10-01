<?php

namespace SMSkin\LaravelRabbitMq\Examples\Consumers;

use PhpAmqpLib\Message\AMQPMessage;
use SMSkin\LaravelRabbitMq\Entities\Consumer;
use SMSkin\LaravelRabbitMq\Examples\Queues\Queue1;

class Consumer1 extends Consumer
{
    public function getQueue(): string
    {
        return (new Queue1())->getName();
    }

    public function handleMessage(AMQPMessage $message): void
    {
        echo "\n--------\n";
        echo static::class . ': ' . $message->body;
        echo "\n--------\n";

        $message->ack();
    }
}
