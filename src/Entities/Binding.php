<?php

namespace SMSkin\LaravelRabbitMq\Entities;

use SMSkin\LaravelRabbitMq\Contracts\IBinding;

abstract class Binding implements IBinding
{
    protected string $routingKey = '';
    protected bool $noWait = false;
    protected array $arguments = [];
    protected int|null $ticket = null;

    public function getRoutingKey(): string
    {
        return $this->routingKey;
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
}
