<?php

namespace SMSkin\LaravelRabbitMq\Entities;

use PhpAmqpLib\Message\AMQPMessage;
use SMSkin\LaravelRabbitMq\Configuration;

abstract class Consumer
{
    protected bool $noLocal = false;
    protected bool $noAck = false;
    protected bool $exclusive = false;
    protected bool $noWait = false;
    protected int|null $ticket = null;
    protected array $arguments = [];

    abstract public function getQueueClass(): string;

    public function getQueue(): Queue
    {
        return app(Configuration::class)->queues->filter(function (Queue $queue) {
            return get_class($queue) === $this->getQueueClass();
        })->firstOrFail();
    }

    abstract public function handleMessage(AMQPMessage $message);

    public function getTag(): string
    {
        return md5(static::class);
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
