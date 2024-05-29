<?php

namespace SMSkin\LaravelRabbitMq\Examples\QueueBindings;

use SMSkin\LaravelRabbitMq\Entities\Binding;
use SMSkin\LaravelRabbitMq\Examples\Exchanges\Exchange3;
use SMSkin\LaravelRabbitMq\Examples\Queues\Queue2;

class QueueBinding2 extends Binding
{
    public function getSource(): string
    {
        return (new Exchange3())->getName();
    }

    public function getDestination(): string
    {
        return (new Queue2())->getName();
    }
}
