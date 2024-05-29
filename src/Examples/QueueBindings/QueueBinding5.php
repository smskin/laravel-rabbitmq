<?php

namespace SMSkin\LaravelRabbitMq\Examples\QueueBindings;

use SMSkin\LaravelRabbitMq\Entities\Binding;
use SMSkin\LaravelRabbitMq\Examples\Exchanges\Exchange1;
use SMSkin\LaravelRabbitMq\Examples\Queues\Queue4;

class QueueBinding5 extends Binding
{
    public function getSource(): string
    {
        return (new Exchange1())->getName();
    }

    public function getDestination(): string
    {
        return (new Queue4())->getName();
    }
}
