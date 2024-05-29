<?php

namespace SMSkin\LaravelRabbitMq\Contracts;

use PhpAmqpLib\Message\AMQPMessage;

interface IConsumer
{
    public function getQueue(): string;

    public function handleMessage(AMQPMessage $message): void;

    public function getTag(): string;

    public function isNoLocal(): bool;

    public function isNoAck(): bool;

    public function isExclusive(): bool;

    public function isNoWait(): bool;

    public function getTicket(): int|null;

    public function getArguments(): array;
}
