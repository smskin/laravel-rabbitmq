<?php

namespace SMSkin\LaravelRabbitMq\Examples\Queues;

use Illuminate\Support\Collection;
use SMSkin\LaravelRabbitMq\Entities\Binding;
use SMSkin\LaravelRabbitMq\Entities\Queue;
use SMSkin\LaravelRabbitMq\Examples\Exchanges\TestExchange3;

class TestQueue3 extends Queue
{
    public function getBindings(): Collection
    {
        return collect([
            new Binding(TestExchange3::class),
        ]);
    }
}
