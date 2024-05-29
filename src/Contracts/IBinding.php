<?php

namespace SMSkin\LaravelRabbitMq\Contracts;

interface IBinding
{
    public function getSource(): string;

    public function getDestination(): string;

    public function getRoutingKey(): string;

    public function isNoWait(): bool;

    public function getArguments(): array;

    public function getTicket(): int|null;
}
