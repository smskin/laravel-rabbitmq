<?php

namespace SMSkin\LaravelRabbitMq;

use Illuminate\Support\Collection;
use SMSkin\LaravelRabbitMq\Entities\Consumer;
use SMSkin\LaravelRabbitMq\Entities\Exchange;
use SMSkin\LaravelRabbitMq\Entities\Queue;

class Configuration
{
    /**
     * @var Collection<Exchange>
     */
    public readonly Collection $exchanges;

    /**
     * @var Collection<Queue>
     */
    public readonly Collection $queues;

    /**
     * @var Collection<Consumer>
     */
    public readonly Collection $consumers;

    public function __construct(array $exchanges, array $queues, array $consumers)
    {
        $this->prepareExchanges($exchanges);
        $this->prepareQueues($queues);
        $this->prepareConsumers($consumers);
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
}
