<?php

namespace SMSkin\LaravelRabbitMq\Entities;

use Illuminate\Support\Collection;
use SMSkin\LaravelRabbitMq\Traits\NameTrait;

abstract class Queue
{
    use NameTrait;

    protected bool $passive = false;
    protected bool $durable = false;
    protected bool $exclusive = false;
    protected bool $autoDelete = false;
    protected bool $noWait = false;
    protected array $arguments = [];
    protected int|null $ticket = null;

    /**
     * @return Collection<Binding>
     */
    public function getBindings(): Collection
    {
        return collect();
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
}
