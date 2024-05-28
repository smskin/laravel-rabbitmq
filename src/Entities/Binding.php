<?php

namespace SMSkin\LaravelRabbitMq\Entities;

class Binding
{
    /** @noinspection ParameterDefaultsNullInspection */
    public function __construct(
        public readonly string $exchangeClass,
        public readonly string $routingKey = '',
        public readonly bool $noWait = false,
        public readonly array $arguments = [],
        public readonly int|null $ticket = null
    ) {
    }
}
