<?php

namespace SMSkin\LaravelRabbitMq\Sharding;

use Illuminate\Support\Collection;
use RuntimeException;
use SMSkin\LaravelRabbitMq\Configuration;
use SMSkin\LaravelRabbitMq\Contracts\IConsumer;
use SMSkin\LaravelRabbitMq\Contracts\IShardingStrategy;
use SMSkin\LaravelRabbitMq\Entities\Worker;
use SMSkin\LaravelSupervisor\Contracts\IWorker;

class EveryConsumerStrategy implements IShardingStrategy
{
    public function __construct(private readonly int|null $maxShards)
    {
    }

    /**
     * @param Configuration $configuration
     * @return Collection<IWorker>
     */
    public function getShards(Configuration $configuration): Collection
    {
        $shards = $configuration->consumers->map(static function (IConsumer $consumer, int $position) {
            return new Worker('rmq:worker ' . $position);
        });
        $shardsCount = $shards->count();
        if ($this->maxShards && $shardsCount > $this->maxShards) {
            throw new RuntimeException('Shards count more than max shards config (' . $shardsCount . '/' . $this->maxShards . ')');
        }
        return $shards;
    }

    /**
     * @param Configuration $configuration
     * @param int $id
     * @return Collection<IConsumer>
     */
    public function getConsumersForShard(Configuration $configuration, int $id): Collection
    {
        if ($this->maxShards && $id > $this->maxShards) {
            throw new RuntimeException('Shard not exists');
        }

        $consumer = $configuration->consumers->values()->get($id);
        if (!$consumer) {
            throw new RuntimeException('Consumer not found (' . $id . ')');
        }

        return collect([$consumer]);
    }
}
