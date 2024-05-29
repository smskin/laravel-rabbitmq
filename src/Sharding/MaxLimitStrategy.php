<?php

namespace SMSkin\LaravelRabbitMq\Sharding;

use Illuminate\Support\Collection;
use RuntimeException;
use SMSkin\LaravelRabbitMq\Configuration;
use SMSkin\LaravelRabbitMq\Contracts\IConsumer;
use SMSkin\LaravelRabbitMq\Contracts\IShardingStrategy;
use SMSkin\LaravelRabbitMq\Entities\Worker;
use SMSkin\LaravelSupervisor\Contracts\IWorker;

class MaxLimitStrategy implements IShardingStrategy
{
    public function __construct(private readonly int|null $maxShards)
    {
        if (!$this->maxShards) {
            throw new RuntimeException('Max shards config not defined');
        }
    }

    /**
     * @param Configuration $configuration
     * @return Collection<IWorker>
     */
    public function getShards(Configuration $configuration): Collection
    {
        if ($this->maxShards === 1) {
            return (new OneShardStrategy())->getShards($configuration);
        }

        $countOfConsumers = $configuration->consumers->count();
        if ($this->maxShards > $countOfConsumers) {
            return (new EveryConsumerStrategy($this->maxShards))->getShards($configuration);
        }

        return collect(array_fill(0, $this->maxShards, ''))->map(static function ($item, $index) {
            return new Worker('rmq:worker ' . $index);
        });
    }

    /**
     * @param Configuration $configuration
     * @param int $id
     * @return Collection<IConsumer>
     */
    public function getConsumersForShard(Configuration $configuration, int $id): Collection
    {
        if ($id >= $this->maxShards) {
            throw new RuntimeException('Shard not exists');
        }

        if ($this->maxShards === 1) {
            return (new OneShardStrategy())->getConsumersForShard($configuration, $id);
        }

        $countOfConsumers = $configuration->consumers->count();
        if ($this->maxShards > $countOfConsumers) {
            return (new EveryConsumerStrategy($this->maxShards))->getConsumersForShard($configuration, $id);
        }

        $limit = round($countOfConsumers / $this->maxShards);
        $chunks = $configuration->consumers->chunk($limit);
        return $chunks->values()->get($id);
    }
}
