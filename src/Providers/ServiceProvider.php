<?php

namespace SMSkin\LaravelRabbitMq\Providers;

use Illuminate\Support\Facades\Config;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use SMSkin\LaravelRabbitMq\Commands\SupervisorCommand;
use SMSkin\LaravelRabbitMq\Commands\WorkerCommand;
use SMSkin\LaravelRabbitMq\Configuration;
use SMSkin\LaravelRabbitMq\Contracts\IConfiguration;
use SMSkin\LaravelRabbitMq\Contracts\IShardingController;
use SMSkin\LaravelRabbitMq\ShardingController;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->commands([
            SupervisorCommand::class,
            WorkerCommand::class,
        ]);

        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->registerConfigs();
    }

    public function register()
    {
        $this->mergeConfigs();

        $this->app->singleton(AMQPStreamConnection::class, static function () {
            return new AMQPStreamConnection(
                Config::get('rabbitmq.connection.host'),
                Config::get('rabbitmq.connection.port'),
                Config::get('rabbitmq.connection.user'),
                Config::get('rabbitmq.connection.password'),
                Config::get('rabbitmq.connection.vhost'),
                Config::get('rabbitmq.connection.insist'),
                Config::get('rabbitmq.connection.login_method'),
                null,
                Config::get('rabbitmq.connection.locale'),
                Config::get('rabbitmq.connection.connection_timeout'),
                Config::get('rabbitmq.connection.read_write_timeout'),
                null,
                Config::get('rabbitmq.connection.keepalive'),
                Config::get('rabbitmq.connection.heartbeat'),
                Config::get('rabbitmq.connection.channel_rpc_timeout'),
            );
        });

        $this->app->bind(IConfiguration::class, Configuration::class);

        $this->app->singleton(Configuration::class, static function () {
            return new Configuration(
                Config::get('rabbitmq.exchanges'),
                Config::get('rabbitmq.queues'),
                Config::get('rabbitmq.consumers'),
                Config::get('rabbitmq.exchange_bindings'),
                Config::get('rabbitmq.queue_bindings'),
            );
        });

        $this->app->bind(IShardingController::class, ShardingController::class);

        $this->app->singleton(ShardingController::class, static function () {
            return new ShardingController(
                Config::get('rabbitmq.sharding.strategy'),
                Config::get('rabbitmq.sharding.max_shards'),
                app(IConfiguration::class)
            );
        });
    }

    private function registerConfigs()
    {
        $this->publishes([
            __DIR__ . '/../../config/rabbitmq.php' => $this->app->configPath('rabbitmq.php'),
        ], 'config');
    }

    private function mergeConfigs()
    {
        $configPath = __DIR__ . '/../../config/rabbitmq.php';
        $this->mergeConfigFrom($configPath, 'rabbitmq');
    }
}
