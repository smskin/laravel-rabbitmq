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
    'exchanges' => [
//        \SMSkin\LaravelRabbitMq\Examples\Exchanges\Exchange1::class,
//        \SMSkin\LaravelRabbitMq\Examples\Exchanges\Exchange2::class,
//        \SMSkin\LaravelRabbitMq\Examples\Exchanges\Exchange3::class,
//        \SMSkin\LaravelRabbitMq\Examples\Exchanges\Exchange4::class,
    ],
    'exchange_bindings' => [
//        \SMSkin\LaravelRabbitMq\Examples\ExchangeBindings\ExchangeBinding1::class,
//        \SMSkin\LaravelRabbitMq\Examples\ExchangeBindings\ExchangeBinding2::class,
    ],
    'queues' => [
//        \SMSkin\LaravelRabbitMq\Examples\Queues\Queue1::class,
//        \SMSkin\LaravelRabbitMq\Examples\Queues\Queue2::class,
//        \SMSkin\LaravelRabbitMq\Examples\Queues\Queue3::class,
//        \SMSkin\LaravelRabbitMq\Examples\Queues\Queue4::class,
    ],
    'queue_bindings' => [
//        \SMSkin\LaravelRabbitMq\Examples\QueueBindings\QueueBinding1::class,
//        \SMSkin\LaravelRabbitMq\Examples\QueueBindings\QueueBinding2::class,
//        \SMSkin\LaravelRabbitMq\Examples\QueueBindings\QueueBinding3::class,
//        \SMSkin\LaravelRabbitMq\Examples\QueueBindings\QueueBinding4::class,
//        \SMSkin\LaravelRabbitMq\Examples\QueueBindings\QueueBinding5::class,
    ],
    'consumers' => [
//        \SMSkin\LaravelRabbitMq\Examples\Consumers\Consumer1::class,
//        \SMSkin\LaravelRabbitMq\Examples\Consumers\Consumer2::class,
//        \SMSkin\LaravelRabbitMq\Examples\Consumers\Consumer3::class,
    ],
];
