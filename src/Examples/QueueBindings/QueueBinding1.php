<?php

namespace SMSkin\LaravelRabbitMq\Examples\QueueBindings;

use SMSkin\LaravelRabbitMq\Entities\Binding;
use SMSkin\LaravelRabbitMq\Examples\Exchanges\Exchange2;
use SMSkin\LaravelRabbitMq\Examples\Queues\Queue1;

class QueueBinding1 extends Binding
{
    public function getSource(): string
    {
        return (new Exchange2())->getName();
    }

    public function getDestination(): string
    {
        return (new Queue1())->getName();
    }
}
