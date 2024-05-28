<?php

namespace SMSkin\LaravelRabbitMq\Commands;

use Illuminate\Support\Collection;
use SMSkin\LaravelRabbitMq\ConfigurationResolver;
use SMSkin\LaravelRabbitMq\ShardingController;
use SMSkin\LaravelSupervisor\Commands\SupervisorsCommand as BaseCommand;
use SMSkin\LaravelSupervisor\Contracts\IWorker;

class SupervisorCommand extends BaseCommand
{
    protected $signature = 'rmq:supervisor';

    protected $description = 'Run RabbitMQ daemons supervisor';

    public function __construct(private readonly ShardingController $shardingController)
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->configureRabbitMq();
        $this->start();
    }

    /**
     * @return Collection<IWorker>
     */
    protected function getWorkers(): Collection
    {
        return $this->shardingController->getShards();
    }

    private function configureRabbitMq()
    {
        app(ConfigurationResolver::class)->declare();
    }
}