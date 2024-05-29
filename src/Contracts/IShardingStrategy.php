<?php

namespace SMSkin\LaravelRabbitMq\Contracts;

use Illuminate\Support\Collection;
use SMSkin\LaravelRabbitMq\Configuration;
use SMSkin\LaravelSupervisor\Contracts\IWorker;

interface IShardingStrategy
{
    /**
     * @param Configuration $configuration
     * @return Collection<IWorker>
     */
    public function getShards(Configuration $configuration): Collection;

    /**
     * @param Configuration $configuration
     * @param int $id
     * @return Collection<IConsumer>
     */
    public function getConsumersForShard(Configuration $configuration, int $id): Collection;
}
