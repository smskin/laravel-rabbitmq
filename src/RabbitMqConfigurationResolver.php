<?php

namespace SMSkin\LaravelRabbitMq;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use SMSkin\LaravelRabbitMq\Contracts\IBinding;
use SMSkin\LaravelRabbitMq\Contracts\IConfiguration;
use SMSkin\LaravelRabbitMq\Contracts\IExchange;
use SMSkin\LaravelRabbitMq\Contracts\IQueue;
use SMSkin\LaravelRabbitMq\Enums\ExchangeType;

class RabbitMqConfigurationResolver
{
    public function __construct(private readonly AMQPStreamConnection $connection, private readonly IConfiguration $configuration)
    {
    }

    public function getConnection(): AMQPStreamConnection
    {
        return $this->connection;
    }

    /**
     * @throws Exception
     */
    public function declare(): void
    {
        $this->declareExchanges();
        $this->declareQueues();
        $this->declareErrorQueues();
        $this->declareExchangeBindings();
        $this->declareQueueBindings();
    }

    private function declareExchanges(): void
    {
        $this->configuration->getExchanges()->each(function (IExchange $exchange) {
            $this->connection->channel()->exchange_declare(
                $exchange->getName(),
                $exchange->getType()->value,
                $exchange->isPassive(),
                $exchange->isDurable(),
                $exchange->isAutoDelete(),
                $exchange->isInternal(),
                $exchange->isNoWait(),
                $exchange->getArguments(),
                $exchange->getTicket()
            );
        });
    }

    private function declareQueues(): void
    {
        $this->configuration->getQueues()->each(function (IQueue $queue) {
            $this->connection->channel()->queue_declare(
                $queue->getName(),
                $queue->isPassive(),
                $queue->isDurable(),
                $queue->isExclusive(),
                $queue->isAutoDelete(),
                $queue->isNoWait(),
                $queue->getArguments(),
                $queue->getTicket()
            );
        });
    }

    private function declareErrorQueues(): void
    {
        $this->configuration->getQueues()->each(function (IQueue $queue) {
            $this->declareErrorQueue($queue);
        });
    }

    private function declareErrorQueue(IQueue $queue): void
    {
        $channel = $this->connection->channel();
        $name = $queue->getName() . '_error';

        $channel->exchange_declare($name, ExchangeType::DIRECT->value);
        $channel->queue_declare($name);
        $channel->queue_bind($name, $name);
    }

    private function declareExchangeBindings(): void
    {
        $this->configuration->getExchangeBindings()->each(function (IBinding $binding) {
            $this->connection->channel()->exchange_bind(
                $binding->getDestination(),
                $binding->getSource(),
                $binding->getRoutingKey(),
                $binding->isNoWait(),
                $binding->getArguments(),
                $binding->getTicket()
            );
        });
    }

    private function declareQueueBindings(): void
    {
        $this->configuration->getQueueBindings()->each(function (IBinding $binding) {
            $this->connection->channel()->queue_bind(
                $binding->getDestination(),
                $binding->getSource(),
                $binding->getRoutingKey(),
                $binding->isNoWait(),
                $binding->getArguments(),
                $binding->getTicket()
            );
        });
    }
}
