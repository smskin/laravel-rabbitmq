<?php

namespace SMSkin\LaravelRabbitMq\Entities;

use PhpAmqpLib\Message\AMQPMessage;
use SMSkin\LaravelRabbitMq\Contracts\IConsumer;

abstract class Consumer implements IConsumer
{
    protected string $tag;
    protected bool $noLocal = false;
    protected bool $noAck = false;
    protected bool $exclusive = false;
    protected bool $noWait = false;
    protected int|null $ticket = null;
    protected array $arguments = [];

    public function __construct()
    {
        $this->tag = md5(static::class);
    }

    abstract public function handleMessage(AMQPMessage $message): void;

    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @return bool
     */
    public function isNoLocal(): bool
    {
        return $this->noLocal;
    }

    /**
     * @return bool
     */
    public function isNoAck(): bool
    {
        return $this->noAck;
    }

    /**
     * @return bool
     */
    public function isExclusive(): bool
    {
        return $this->exclusive;
    }

    /**
     * @return bool
     */
    public function isNoWait(): bool
    {
        return $this->noWait;
    }

    /**
     * @return int|null
     */
    public function getTicket(): int|null
    {
        return $this->ticket;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
