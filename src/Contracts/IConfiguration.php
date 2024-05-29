<?php

namespace SMSkin\LaravelRabbitMq\Contracts;

use Illuminate\Support\Collection;

interface IConfiguration
{
    /**
     * @return Collection<IExchange>
     */
    public function getExchanges(): Collection;

    /**
     * @param Collection<IExchange> $collection
     */
    public function setExchanges(Collection $collection): static;

    public function addExchange(IExchange $exchange): static;

    /**
     * @return Collection<IQueue>
     */
    public function getQueues(): Collection;

    /**
     * @param Collection<IQueue> $queues
     */
    public function setQueues(Collection $queues): static;

    public function addQueue(IQueue $queue): static;

    /**
     * @return Collection<IConsumer>
     */
    public function getConsumers(): Collection;

    /**
     * @param Collection<IConsumer> $consumers
     */
    public function setConsumers(Collection $consumers): static;

    public function addConsumer(IConsumer $consumer): static;

    /**
     * @return Collection<IBinding>
     */
    public function getExchangeBindings(): Collection;

    /**
     * @param Collection<IBinding> $bindings
     */
    public function setExchangeBindings(Collection $bindings): static;

    public function addExchangeBinding(IBinding $binding): static;

    /**
     * @return Collection<IBinding>
     */
    public function getQueueBindings(): Collection;

    /**
     * @param Collection<IBinding> $bindings
     */
    public function setQueueBindings(Collection $bindings): static;

    public function addQueueBinding(IBinding $binding): static;
}
