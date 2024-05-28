<?php

namespace SMSkin\LaravelRabbitMq\Examples\Consumers;

use PhpAmqpLib\Message\AMQPMessage;
use SMSkin\LaravelRabbitMq\Entities\Consumer;
use SMSkin\LaravelRabbitMq\Examples\Queues\TestQueue1;

class TestConsumer1 extends Consumer
{
    public function getQueueClass(): string
    {
        return TestQueue1::class;
    }

    public function handleMessage(AMQPMessage $message)
    {
        echo "\n--------\n";
        echo static::class . ': ' . $message->body;
        echo "\n--------\n";
    }
}
