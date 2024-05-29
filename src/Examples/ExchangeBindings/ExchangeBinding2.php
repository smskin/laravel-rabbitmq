<?php

namespace SMSkin\LaravelRabbitMq\Examples\ExchangeBindings;

use SMSkin\LaravelRabbitMq\Entities\Binding;
use SMSkin\LaravelRabbitMq\Examples\Exchanges\Exchange1;
use SMSkin\LaravelRabbitMq\Examples\Exchanges\Exchange3;

class ExchangeBinding2 extends Binding
{
    public function getSource(): string
    {
        return (new Exchange1())->getName();
    }

    public function getDestination(): string
    {
        return (new Exchange3())->getName();
    }
}
