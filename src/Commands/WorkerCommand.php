<?php

namespace SMSkin\LaravelRabbitMq\Commands;

use ErrorException;
use Exception;
use SMSkin\LaravelRabbitMq\ShardingController;
use SMSkin\LaravelRabbitMq\Worker;
use SMSkin\LaravelSupervisor\Commands\WorkerCommand as BaseCommand;

class WorkerCommand extends BaseCommand
{
    protected $signature = 'rmq:worker {shardId}';

    protected $description = 'Run worker';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws ErrorException
     */
    public function handle()
    {
        $consumers = app(ShardingController::class)->getConsumersForShard($this->argument('shardId'));
        app(Worker::class)->start($consumers);
    }

    public function getSubscribedSignals(): array
    {
        return [SIGINT, SIGTERM];
    }

    /**
     * @throws Exception
     */
    public function handleSignal(int $signal, false|int $previousExitCode = 0): int|false
    {
        $this->logInfo('Signal received - ' . $signal);
        switch ($signal) {
            case SIGTERM:
            case SIGQUIT:
                $this->worker->terminate();
                $this->logInfo('Worker terminated (hard)');
                break;
            case SIGINT:   // 2  : ctrl+c
                $this->worker->terminate();
                $this->logInfo('Worker terminated (soft)');
                break;
        }
        return false;
    }

    private function logInfo(string $message)
    {
        $this->info('Worker ' . $this->argument('shardId') . ': ' . $message);
    }
}
