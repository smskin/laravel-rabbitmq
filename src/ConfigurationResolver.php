<?php

namespace SMSkin\LaravelRabbitMq;

use Illuminate\Support\Collection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use SMSkin\LaravelRabbitMq\Entities\Binding;
use SMSkin\LaravelRabbitMq\Entities\Exchange;
use SMSkin\LaravelRabbitMq\Entities\Queue;
use SMSkin\LaravelRabbitMq\Enums\ExchangeType;

class ConfigurationResolver
{
    public function __construct(private readonly AMQPStreamConnection $connection, private readonly Configuration $configuration)
    {
    }

    public function declare(): void
    {
        $configuration = $this->configuration;
        $channel = $this->connection->channel();

        $this->declareExchanges($channel, $configuration->exchanges);
        $this->declareQueues($channel, $configuration->queues, $configuration->exchanges);
        $this->declareErrorQueues($channel, $configuration->queues);
    }

    /**
     * @param AMQPChannel $channel
     * @param Collection<Exchange> $exchanges
     */
    private function declareExchanges(AMQPChannel $channel, Collection $exchanges): void
    {
        $exchanges->each(function (Exchange $exchange) use ($channel, $exchanges) {
            $channel->exchange_declare(
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

            $exchange->getBindings()->each(function (Binding $binding) use ($channel, $exchanges, $exchange) {
                $this->declareExchangeBind($channel, $exchanges, $exchange, $binding);
            });
        });
    }

    private function declareExchangeBind(AMQPChannel $channel, Collection $exchanges, Exchange $exchange, Binding $binding): void
    {
        /**
         * @var $source Exchange
         */
        $source = $exchanges->filter(static function (Exchange $exchange) use ($binding) {
            return get_class($exchange) === $binding->exchangeClass;
        })->firstOrFail();

        $channel->exchange_bind(
            $exchange->getName(),
            $source->getName(),
            $binding->routingKey,
            $binding->noWait,
            $binding->arguments,
            $binding->ticket
        );
    }

    /**
     * @param AMQPChannel $channel
     * @param Collection<Queue> $queues
     * @param Collection<Exchange> $exchanges
     */
    private function declareQueues(AMQPChannel $channel, Collection $queues, Collection $exchanges): void
    {
        $queues->each(function (Queue $queue) use ($channel, $exchanges) {
            $channel->queue_declare(
                $queue->getName(),
                $queue->isPassive(),
                $queue->isDurable(),
                $queue->isExclusive(),
                $queue->isAutoDelete(),
                $queue->isNoWait(),
                $queue->getArguments(),
                $queue->getTicket()
            );

            $queue->getBindings()->each(function (Binding $binding) use ($channel, $exchanges, $queue) {
                $this->declareQueueBind($channel, $exchanges, $queue, $binding);
            });
        });
    }

    private function declareQueueBind(AMQPChannel $channel, Collection $exchanges, Queue $queue, Binding $binding): void
    {
        /**
         * @var $exchange Exchange
         */
        $exchange = $exchanges->filter(static function (Exchange $exchange) use ($binding) {
            return get_class($exchange) === $binding->exchangeClass;
        })->firstOrFail();

        $channel->queue_bind(
            $queue->getName(),
            $exchange->getName(),
            $binding->routingKey,
            $binding->noWait,
            $binding->arguments,
            $binding->ticket
        );
    }

    /**
     * @param AMQPChannel $channel
     * @param Collection<Queue> $queues
     */
    private function declareErrorQueues(AMQPChannel $channel, Collection $queues): void
    {
        $queues->each(function (Queue $queue) use ($channel) {
            $this->declareErrorQueue($channel, $queue);
        });
    }

    private function declareErrorQueue(AMQPChannel $channel, Queue $queue): void
    {
        $name = $queue->getName() . '_error';
        $channel->exchange_declare($name, ExchangeType::DIRECT->value);
        $channel->queue_declare($name);
        $channel->queue_bind($name, $name);
    }
}
