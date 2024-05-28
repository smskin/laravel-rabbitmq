<?php

namespace SMSkin\LaravelRabbitMq\Examples\Exchanges;

use Illuminate\Support\Collection;
use SMSkin\LaravelRabbitMq\Entities\Binding;
use SMSkin\LaravelRabbitMq\Entities\Exchange;

class TestExchange3 extends Exchange
{
    public function getBindings(): Collection
    {
        return collect([
            new Binding(TestExchange1::class),
        ]);
    }
}
