<?php

namespace SMSkin\LaravelRabbitMq\Contracts;

interface IQueue
{
    public function isPassive(): bool;

    public function isDurable(): bool;

    public function isExclusive(): bool;

    public function isAutoDelete(): bool;

    public function isNoWait(): bool;

    public function getArguments(): array;

    public function getTicket(): int|null;

    public function setName(string $name): self;

    public function getName(): string;
}
