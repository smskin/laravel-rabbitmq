<?php

namespace SMSkin\LaravelRabbitMq\Entities;

use SMSkin\LaravelRabbitMq\Contracts\IExchange;
use SMSkin\LaravelRabbitMq\Enums\ExchangeType;
use SMSkin\LaravelRabbitMq\Traits\NameTrait;

abstract class Exchange implements IExchange
{
    use NameTrait;

    protected string $name;
    protected ExchangeType $type = ExchangeType::FANOUT;
    protected bool $passive = false;
    protected bool $durable = false;
    protected bool $autoDelete = false;
    protected bool $internal = false;
    protected bool $noWait = false;
    protected array $arguments = [];
    protected int|null $ticket = null;

    public function __construct()
    {
        $this->name = $this->generateName();
    }

    public function getType(): ExchangeType
    {
        return $this->type;
    }

    public function isPassive(): bool
    {
        return $this->passive;
    }

    public function isDurable(): bool
    {
        return $this->durable;
    }

    public function isAutoDelete(): bool
    {
        return $this->autoDelete;
    }

    public function isInternal(): bool
    {
        return $this->internal;
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

    /**
     * @param string $name
     * @return Exchange
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
