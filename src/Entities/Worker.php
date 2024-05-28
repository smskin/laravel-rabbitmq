<?php

namespace SMSkin\LaravelRabbitMq\Entities;

use SMSkin\LaravelSupervisor\Contracts\IWorker;

class Worker implements IWorker
{
    public function __construct(private readonly string $artisanCommand)
    {
    }

    public function getArtisanCommand(): string
    {
        return $this->artisanCommand;
    }
}
