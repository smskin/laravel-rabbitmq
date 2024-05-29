<?php

namespace SMSkin\LaravelRabbitMq\Contracts;

use SMSkin\LaravelRabbitMq\Enums\ExchangeType;

interface IExchange
{
    public function getType(): ExchangeType;

    public function isPassive(): bool;

    public function isDurable(): bool;

    public function isAutoDelete(): bool;

    public function isInternal(): bool;

    public function isNoWait(): bool;

    public function getArguments(): array;

    public function getTicket(): int|null;

    public function setName(string $name): self;

    public function getName(): string;
}
