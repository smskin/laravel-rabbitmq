<?php

namespace SMSkin\LaravelRabbitMq\Enums;

enum ExchangeType: string
{
    case DIRECT = 'direct';
    case FANOUT = 'fanout';
    case TOPIC = 'topic';
    case HEADERS = 'headers';
}
