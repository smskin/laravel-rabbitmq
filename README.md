# RabbitMQ (AMQP) Library for Laravel

This library is based on the [smskin/laravel-daemon-supervisor](https://packagist.org/packages/smskin/laravel-daemon-supervisor) and [https://packagist.org/packages/php-amqplib/php-amqplib](php-amqplib/php-amqplib) libraries.

The library allows you to configure, listen to RabbitMQ queues, and send messages to the broker's exchange.

## Installation
```bash
composer required smskin/laravel-rabbitmq
php artisan vendor:publish --provider="SMSkin\LaravelRabbitMq\Providers\ServiceProvider"
```
## Configuration

Settings are located in the `config/rabbitmq.php` file.

### Connection Settings
RabbitMQ connection settings.

### Sharding - Load Balancing
Settings for distributing messages across incoming message handler processes.

`strategy` - sharding strategy.

Available strategies:

* ONE_SHARD - one process listens to all messages
* EVERY_CONSUMER - each consumer has a separate process
* MAX_LIMIT - launches `max_shards` processes, messages are distributed evenly among them

### Exchanges
Registration of RabbitMQ exchanges.

### Exchange Bindings
Registration of bindings between Exchanges.

### Queues
Registration of queues.

### Queue Bindings
Registration of bindings from Exchanges to queues.

### Consumers
Registration of incoming message handlers.

## Structure

### Exchange
Class inherits from `SMSkin\LaravelRabbitMq\Entities\Exchange`.

### Exchange Binding
Allows routing messages from one exchange to others.

Class inherits from `SMSkin\LaravelRabbitMq\Entities\Binding`.

Refer to the base class for configuration rules as per AMQP documentation.

Methods to implement:
* `public function getSource(): string` - Returns the source Exchange name.
* `public function getDestination(): string` - Returns the destination Exchange name.

Refer to the base class for configuration rules as per AMQP documentation.

### Queue
Class inherits from `SMSkin\LaravelRabbitMq\Entities\Queue`. 

Refer to the base class for configuration rules as per AMQP documentation.

### Queue Binding
Class inherits from `SMSkin\LaravelRabbitMq\Entities\Binding`.

Allows routing messages from an Exchange to a queue.

Methods to implement:
* `public function getSource(): string` - Returns the source Exchange name.
* `public function getDestination(): string` - Returns the destination Queue name.

Refer to the base class for configuration rules as per AMQP documentation.

### Consumer
Incoming message handler. Class inherits from `SMSkin\LaravelRabbitMq\Entities\Consumer`.

Methods to implement:
* `public function getQueue(): string` - Returns the name of the queue this handler listens to.
* `public function handleMessage(AMQPMessage $message): void` - Handles the message.

* The `handleMessage(AMQPMessage $message)` method should end with marking the message as processed (`$message->ack();`).

Aim for the `handleMessage(AMQPMessage $message)` method to execute in minimal time to avoid becoming a bottleneck in queue processing.

## Logic

This library includes 2 artisan commands:
* `rmq:supervisor` - Master process
* `rmq:worker` - Worker process

When running `rmq:supervisor`, the library configures RabbitMQ according to the configuration file and starts worker processes based on the selected strategy (`rabbitmq.sharding.strategy`).

When a message appears in the listening queue, the `handleMessage(AMQPMessage $message)` method of the subscribed handler is triggered.

## Usage in Laravel

Add the command `php artisan rmq:supervisor` to your [supervisor](https://www.digitalocean.com/community/tutorials/how-to-install-and-manage-supervisor-on-ubuntu-and-debian-vps) configuration. 
Upon starting, supervisor will launch the master process.

```text
[program:laravel-rmq-supervisor]
process_name=%(program_name)s
command=php /var/www/html/artisan rmq:supervisor
autostart=true
autorestart=true
user=www-data
group=www-data
redirect_stderr=true
stdout_logfile=/dev/stdout
stderr_logfile=/dev/stderr
stdout_maxbytes=0
stderr_maxbytes=0
stdout_logfile_maxbytes = 0
stderr_logfile_maxbytes = 0
startsecs=0
stopwaitsecs=3600
```
