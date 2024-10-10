<?php

namespace SMSkin\LaravelRabbitMq\Commands;

use Exception;
use Illuminate\Support\Collection;
use SMSkin\LaravelRabbitMq\Contracts\IShardingController;
use SMSkin\LaravelRabbitMq\RabbitMqConfigurationResolver;
use SMSkin\LaravelSupervisor\Commands\SupervisorsCommand as BaseCommand;
use SMSkin\LaravelSupervisor\Contracts\IWorker;

class SupervisorCommand extends BaseCommand
{
    protected $signature = 'rmq:supervisor';

    protected $description = 'Run RabbitMQ daemons supervisor';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        $configuration = $this->getConfigResolver();
        $configuration->declare();
        $configuration->getConnection()->close();

        $this->start();
    }


    /**
     * @return Collection<IWorker>
     */
    protected function getWorkers(): Collection
    {
        return app(IShardingController::class)->getShards();
    }

    private function getConfigResolver(): RabbitMqConfigurationResolver
    {
        return app(RabbitMqConfigurationResolver::class);
    }
}
