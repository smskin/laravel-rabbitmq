<?php

namespace SMSkin\LaravelRabbitMq;

use Illuminate\Support\Collection;
use SMSkin\LaravelRabbitMq\Contracts\IShardingStrategy;
use SMSkin\LaravelRabbitMq\Entities\Consumer;
use SMSkin\LaravelRabbitMq\Enums\ShardingStrategy;
use SMSkin\LaravelRabbitMq\Sharding\EveryConsumerStrategy;
use SMSkin\LaravelRabbitMq\Sharding\MaxLimitStrategy;
use SMSkin\LaravelRabbitMq\Sharding\OneShardStrategy;
use SMSkin\LaravelSupervisor\Contracts\IWorker;

class ShardingController
{
    public function __construct(private readonly ShardingStrategy $shardingStrategy, private readonly int|null $maxShards, private readonly Configuration $configuration)
    {
    }

    /**
     * @return Collection<IWorker>
     */
    public function getShards(): Collection
    {
        return $this->getStrategy()->getShards($this->configuration);
    }

    /**
     * @param int $id
     * @return Collection<Consumer>
     */
    public function getConsumersForShard(int $id): Collection
    {
        return $this->getStrategy()->getConsumersForShard($this->configuration, $id);
    }

    private function getStrategy(): IShardingStrategy
    {
        return match ($this->shardingStrategy) {
            ShardingStrategy::ONE_SHARD => new OneShardStrategy(),
            ShardingStrategy::EVERY_CONSUMER => new EveryConsumerStrategy($this->maxShards),
            ShardingStrategy::MAX_LIMIT => new MaxLimitStrategy($this->maxShards)
        };
    }
}
