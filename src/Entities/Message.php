<?php

namespace SMSkin\LaravelRabbitMq\Entities;

use PhpAmqpLib\Message\AMQPMessage;

class Message
{
    /** @noinspection ParameterDefaultsNullInspection */
    public function __construct(
        public readonly AMQPMessage $message,
        public readonly string $exchangeName,
        public readonly string $routingKey = '',
        public readonly bool $mandatory = false,
        public readonly bool $immediate = false,
        public readonly int|null $ticket = null
    ) {
    }
}
