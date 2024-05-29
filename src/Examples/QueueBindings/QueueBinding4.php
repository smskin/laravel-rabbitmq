<?php

namespace SMSkin\LaravelRabbitMq\Examples\QueueBindings;

use SMSkin\LaravelRabbitMq\Entities\Binding;
use SMSkin\LaravelRabbitMq\Examples\Exchanges\Exchange4;
use SMSkin\LaravelRabbitMq\Examples\Queues\Queue4;

class QueueBinding4 extends Binding
{
    public function getSource(): string
    {
        return (new Exchange4())->getName();
    }

    public function getDestination(): string
    {
        return (new Queue4())->getName();
    }
}
