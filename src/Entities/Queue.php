<?php

namespace SMSkin\LaravelRabbitMq\Entities;

use SMSkin\LaravelRabbitMq\Contracts\IQueue;
use SMSkin\LaravelRabbitMq\Traits\NameTrait;

abstract class Queue implements IQueue
{
    use NameTrait;

    protected string $name;
    protected bool $passive = false;
    protected bool $durable = true;
    protected bool $exclusive = false;
    protected bool $autoDelete = false;
    protected bool $noWait = false;
    protected array $arguments = [];
    protected int|null $ticket = null;

    public function __construct()
    {
        $this->name = $this->generateName();
    }

    public function isPassive(): bool
    {
        return $this->passive;
    }

    public function isDurable(): bool
    {
        return $this->durable;
    }

    public function isExclusive(): bool
    {
        return $this->exclusive;
    }

    public function isAutoDelete(): bool
    {
        return $this->autoDelete;
    }

    public function isNoWait(): bool
    {
        return $this->noWait;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getTicket(): int|null
    {
        return $this->ticket;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
