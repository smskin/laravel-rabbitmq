<?php

namespace SMSkin\LaravelRabbitMq\Contracts;

use Illuminate\Support\Collection;
use SMSkin\LaravelRabbitMq\Enums\ShardingStrategy;
use SMSkin\LaravelSupervisor\Contracts\IWorker;

interface IShardingController
{
    public function __construct(ShardingStrategy $shardingStrategy, int|null $maxShards, IConfiguration $configuration);

    /**
     * @return Collection<IWorker>
     */
    public function getShards(): Collection;

    /**
     * @param int $id
     * @return Collection<IConsumer>
     */
    public function getConsumersForShard(int $id): Collection;
}
