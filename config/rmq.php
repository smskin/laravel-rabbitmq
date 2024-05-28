<?php

use SMSkin\LaravelRabbitMq\Enums\ShardingStrategy;

return [
    'connection' => [
        'host' => env('RABBITMQ_HOST', 'localhost'),
        'port' => env('RABBITMQ_PORT', 5672),
        'user' => env('RABBITMQ_USER', 'guest'),
        'password' => env('RABBITMQ_PASSWORD', 'guest'),
        'vhost' => env('RABBITMQ_VHOST', '/'),
        'insist' => false,
        'login_method' => 'AMQPLAIN',
        'locale' => 'en_US',
        'connection_timeout' => 3.0,
        'read_write_timeout' => 3.0,
        'keepalive' => false,
        'heartbeat' => 0,
        'channel_rpc_timeout' => 0.0,
    ],
    'sharding' => [
        'strategy' => ShardingStrategy::MAX_LIMIT,
        'max_shards' => 2,
    ],
    'exchanges' => [],
    'queues' => [],
    'consumers' => [],
];
