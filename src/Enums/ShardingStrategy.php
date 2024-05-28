<?php

namespace SMSkin\LaravelRabbitMq\Enums;

enum ShardingStrategy
{
    case ONE_SHARD;
    case EVERY_CONSUMER;
    case MAX_LIMIT;
}
