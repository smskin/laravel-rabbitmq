<?php

namespace SMSkin\LaravelRabbitMq\Examples\Consumers;

use PhpAmqpLib\Message\AMQPMessage;
use SMSkin\LaravelRabbitMq\Entities\Consumer;
use SMSkin\LaravelRabbitMq\Examples\Queues\TestQueue3;

class TestConsumer3 extends Consumer
{
    public function getQueueClass(): string
    {
        return TestQueue3::class;
    }

    public function handleMessage(AMQPMessage $message)
    {
        echo "\n--------\n";
        echo static::class . ': ' . $message->body;
        echo "\n--------\n";
    }
}
