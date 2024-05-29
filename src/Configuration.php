<?php

namespace SMSkin\LaravelRabbitMq;

use Illuminate\Support\Collection;
use SMSkin\LaravelRabbitMq\Contracts\IBinding;
use SMSkin\LaravelRabbitMq\Contracts\IConfiguration;
use SMSkin\LaravelRabbitMq\Contracts\IConsumer;
use SMSkin\LaravelRabbitMq\Contracts\IExchange;
use SMSkin\LaravelRabbitMq\Contracts\IQueue;

class Configuration implements IConfiguration
{
    /**
     * @var Collection<IExchange>
     */
    public readonly Collection $exchanges;

    /**
     * @var Collection<IQueue>
     */
    public readonly Collection $queues;

    /**
     * @var Collection<IConsumer>
     */
    public readonly Collection $consumers;

    /**
     * @var Collection<IBinding>
     */
    public readonly Collection $exchangeBindings;

    /**
     * @var Collection<IBinding>
     */
    public readonly Collection $queueBindings;

    public function __construct(array $exchanges, array $queues, array $consumers, array $exchangeBindings, array $queueBindings)
    {
        $this->prepareExchanges($exchanges);
        $this->prepareQueues($queues);
        $this->prepareConsumers($consumers);
        $this->prepareExchangeBindings($exchangeBindings);
        $this->prepareQueueBindings($queueBindings);
    }

    private function prepareExchanges(array $exchanges): void
    {
        $this->exchanges = collect($exchanges)->map(static function (string $class) {
            return new $class();
        });
    }

    private function prepareQueues(array $queues): void
    {
        $this->queues = collect($queues)->map(static function (string $class) {
            return new $class();
        });
    }

    private function prepareConsumers(array $consumers): void
    {
        $this->consumers = collect($consumers)->map(static function (string $class) {
            return new $class();
        });
    }

    private function prepareExchangeBindings(array $exchangeBindings)
    {
        $this->exchangeBindings = collect($exchangeBindings)->map(static function (string $class) {
            return new $class();
        });
    }

    private function prepareQueueBindings(array $queueBindings)
    {
        $this->queueBindings = collect($queueBindings)->map(static function (string $class) {
            return new $class();
        });
    }

    public function getExchanges(): Collection
    {
        return $this->exchanges;
    }

    public function setExchanges(Collection $collection): static
    {
        $this->exchanges = $collection;
        return $this;
    }

    public function addExchange(IExchange $exchange): static
    {
        $this->exchanges->push($exchange);
        return $this;
    }

    public function getQueues(): Collection
    {
        return $this->queues;
    }

    public function setQueues(Collection $queues): static
    {
        $this->queues = $queues;
        return $this;
    }

    public function addQueue(IQueue $queue): static
    {
        $this->queues->push($queue);
        return $this;
    }

    public function getConsumers(): Collection
    {
        return $this->consumers;
    }

    public function setConsumers(Collection $consumers): static
    {
        $this->consumers = $consumers;
        return $this;
    }

    public function addConsumer(IConsumer $consumer): static
    {
        $this->consumers->push($consumer);
        return $this;
    }

    public function getExchangeBindings(): Collection
    {
        return $this->exchangeBindings;
    }

    public function setExchangeBindings(Collection $bindings): static
    {
        $this->exchangeBindings = $bindings;
        return $this;
    }

    public function addExchangeBinding(IBinding $binding): static
    {
        $this->exchangeBindings->push($binding);
        return $this;
    }

    public function getQueueBindings(): Collection
    {
        return $this->queueBindings;
    }

    public function setQueueBindings(Collection $bindings): static
    {
        $this->queueBindings = $bindings;
        return $this;
    }

    public function addQueueBinding(IBinding $binding): static
    {
        $this->queueBindings->push($binding);
        return $this;
    }
}
