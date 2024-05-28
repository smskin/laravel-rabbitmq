<?php

namespace SMSkin\LaravelRabbitMq\Examples\Queues;

use Illuminate\Support\Collection;
use SMSkin\LaravelRabbitMq\Entities\Binding;
use SMSkin\LaravelRabbitMq\Entities\Queue;
use SMSkin\LaravelRabbitMq\Examples\Exchanges\TestExchange2;

class TestQueue2 extends Queue
{
    public function getBindings(): Collection
    {
        return collect([
            new Binding(TestExchange2::class),
        ]);
    }
}
