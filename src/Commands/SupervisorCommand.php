<?php

namespace SMSkin\LaravelRabbitMq\Commands;

use Illuminate\Support\Collection;
use SMSkin\LaravelRabbitMq\RabbitMqConfigurationResolver;
use SMSkin\LaravelRabbitMq\ShardingController;
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

    public function handle()
    {
        $this->getConfigResolver()->declare();
        $this->start();
    }


    /**
     * @return Collection<IWorker>
     */
    protected function getWorkers(): Collection
    {
        return app(ShardingController::class)->getShards();
    }

    private function getConfigResolver(): RabbitMqConfigurationResolver
    {
        return app(RabbitMqConfigurationResolver::class);
    }
}
