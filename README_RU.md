# Библиотека для работы с RabbitMQ (AMQP) в Laravel

Данная библиотека базируется на библиотеках [smskin/laravel-daemon-supervisor](https://packagist.org/packages/smskin/laravel-daemon-supervisor) и [https://packagist.org/packages/php-amqplib/php-amqplib](php-amqplib/php-amqplib).

Библиотка позволяет конфигурировать, слушать очереди RabbitMQ и отправлять сообщения в exchange брокера.

## Установка
```bash
composer required smskin/laravel-rabbitmq
php artisan vendor:publish --provider="SMSkin\LaravelRabbitMq\Providers\ServiceProvider"
```

## Конфигурация
Настройки находятся в файле `config/rabbitmq.php`.

### Настройки подключения - connection
Настройки подключения к RabbitMQ

### sharding - Балансировка
Настройки распределения сообщений по процессам обработчика входящих сообщений.

`strategy` - стратегия шардирования.

Доступные стратегии:
* ONE_SHARD - один процесс слушает все сообщения
* EVERY_CONSUMER - для каждого консьюмера поднимается отдельный процесс
* MAX_LIMIT - поднимается `max_shards` процессов, сообщения распределяются между ними равномерно

### exchanges - Exchanges
Регистрация [RabbitMQ exchanges](https://www.rabbitmq.com/tutorials/amqp-concepts#exchanges).

### exchange_bindings - Привязка одного exchange к другому
Регистрация привязок Exchange к другим Exchange

### queues - Очереди
Регистрация [очередей](https://www.rabbitmq.com/tutorials/amqp-concepts#queues).

### queue_bindings - Привязка очередей к Exchange
Регистрация привязок Exchange к очередям

### consumers - Обработчики входящих сообщений
Регистрация обработчиков входящих сообщений

## Структура

### Exchange
Класс наследуется от `SMSkin\LaravelRabbitMq\Entities\Exchange`.

### Exchange binding
Позволяет маршрутизировать сообщения из одного exchange в другие.

Класс наследуется от `SMSkin\LaravelRabbitMq\Entities\Binding`.

Посмотрите базовый класс. Доступно конфигурирование правил согласно документации AMQP.

Должны быть реализованы следующие методы:
* `public function getSource(): string` - Метод, возвращающий название Exchange - источника сообщений
* `public function getDestination(): string` - Метод, возвращающий название Exchange - получателя сообщений

Посмотрите базовый класс. Доступно конфигурирование правил согласно документации AMQP.

### Queue
Класс наследуется от `SMSkin\LaravelRabbitMq\Entities\Queue`.
Посмотрите базовый класс. Доступно конфигурирование правил согласно документации AMQP.

### Queue binding
Класс наследуется от `SMSkin\LaravelRabbitMq\Entities\Binding`.

Позволяет маршрутизировать сообщения из Exchange в очередь.

Должны быть реализованы следующие методы:
* `public function getSource(): string` - Метод, возвращающий название Exchange - источника сообщений
* `public function getDestination(): string` - Метод, возвращающий название Queue - получателя сообщений

Посмотрите базовый класс. Доступно конфигурирование правил согласно документации AMQP.

### Consumer
Обработчик входящих сообщений.
Класс наследуется от `SMSkin\LaravelRabbitMq\Entities\Consumer`.

Должны быть реализованы следующие методы:
* `public function getQueue(): string` - Метод, возвращающий название очереди, которую данный обработчик прослушивает.
* `public function handleMessage(AMQPMessage $message): void` - Обработчик сообщения 

Код метода `handleMessage(AMQPMessage $message)` должен заканчиваться командой пометки сообщения как обработанным (`$message->ack();`).

Старайтесь сделать так, чтобы метод `handleMessage(AMQPMessage $message)` выполнялся минимальное время чтобы он не становился бутылочным горлышком процесса обработки очереди.

## Логика работы
В данной библиотке есть 2 artisan команды:
* `rmq:supervisor` - Мастер процесс 
* `rmq:worker` - Рабочий процесс

При запуске `rmq:supervisor` библиотека конфигурирует RabbitMQ в соответствии с файлом конфигурации и запускает рабочие процессы в соответствии с выбранной стратегией (`rabbitmq.sharding.strategy`).

При появлении сообщения в прослушиваемой очереди запускается метод `handleMessage(AMQPMessage $message)` подписанного на очередь обработчика.

## Использование в Laravel
Добавьте команду `php artisan rmq:supervisor` в конфигурацию [supervisor](https://www.digitalocean.com/community/tutorials/how-to-install-and-manage-supervisor-on-ubuntu-and-debian-vps). При старте supervisor запустит мастер процесс.

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
