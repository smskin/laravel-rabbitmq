<?php

namespace SMSkin\LaravelRabbitMq\Sharding;

use Illuminate\Support\Collection;
use RuntimeException;
use SMSkin\LaravelRabbitMq\Configuration;
use SMSkin\LaravelRabbitMq\Contracts\IShardingStrategy;
use SMSkin\LaravelRabbitMq\Entities\Consumer;
use SMSkin\LaravelRabbitMq\Entities\Worker;

class OneShardStrategy implements IShardingStrategy
{
    public function getShards(Configuration $configuration): Collection
    {
        return collect([
            new Worker('rmq:worker 0'),
        ]);
    }

    /**
     * @param Configuration $configuration
     * @param int $id
     * @return Collection<Consumer>
     */
    public function getConsumersForShard(Configuration $configuration, int $id): Collection
    {
        if ($id > 0) {
            throw new RuntimeException('Shard not exists');
        }

        return $configuration->consumers;
    }
}
