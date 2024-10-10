<?php

namespace SMSkin\LaravelRabbitMq;

use ErrorException;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use LogicException;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPChannelClosedException;
use PhpAmqpLib\Exception\AMQPConnectionBlockedException;
use PhpAmqpLib\Exception\AMQPConnectionClosedException;
use PhpAmqpLib\Message\AMQPMessage;
use SMSkin\LaravelRabbitMq\Contracts\IConsumer;
use SMSkin\LaravelRabbitMq\Entities\Message;
use Throwable;

class Worker
{
    private AMQPChannel $channel;
    private bool $working;

    /**
     * @var Collection<IConsumer>
     */
    private Collection $consumers;

    /**
     * @param AMQPStreamConnection $connection
     */
    public function __construct(private readonly AMQPStreamConnection $connection)
    {
        $this->channel = $connection->channel();
    }

    /**
     * @param Collection<IConsumer> $consumers
     * @throws ErrorException
     */
    public function start(Collection $consumers): void
    {
        $this->working = true;
        $this->consumers = $consumers;
        $consumers->each(function (IConsumer $consumer) {
            return $this->registerConsumer($consumer);
        });

        $this->log('Worker started');
        $this->channel->consume();
    }

    /**
     * @throws Exception
     */
    public function terminate(): void
    {
        $this->log('Worker terminating');
        $this->working = false;
        sleep(1);
        $this->consumers->each(function (IConsumer $consumer) {
            $this->stopConsumer($consumer);
        });
        $this->connection->close();
        $this->log('Worker terminated');
    }

    private function stopConsumer(IConsumer $consumer): void
    {
        $this->channel->basic_cancel($consumer->getTag());
    }

    private function registerConsumer(IConsumer $consumer): string
    {
        return $this->channel->basic_consume(
            $consumer->getQueue(),
            $consumer->getTag(),
            $consumer->isNoLocal(),
            $consumer->isNoAck(),
            $consumer->isExclusive(),
            $consumer->isNoWait(),
            function (AMQPMessage $message) use ($consumer) {
                $this->log('Handled message', ['body' => $message->body]);
                try {
                    $consumer->handleMessage($message);
                    $this->log('Consumer executed');
                } catch (Throwable $exception) {
                    $this->log('Consumer exception', [
                        'class' => get_class($exception),
                        'message' => $exception->getMessage(),
                    ]);
                    $this->handleException($consumer, $message, $exception);
                }

                try {
                    $message->ack();
                } catch (LogicException $exception) {
                    if (Str::contains($exception->getMessage(), 'Message is not published or response was already sent')) {
                        return;
                    }
                    throw $exception;
                }

                if (!$this->working) {
                    $message->getChannel()->basic_cancel($message->getConsumerTag());
                }
            }
        );
    }

    /**
     * @throws Exception
     */
    private function handleException(IConsumer $consumer, AMQPMessage $message, Throwable $exception): void
    {
        try {
            $this->getPublisher()->publish(new Message(
                new AMQPMessage(json_encode([
                    'exception' => [
                        'class' => get_class($exception),
                        'message' => $exception->getMessage(),
                        'code' => $exception->getCode(),
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                        'trace' => $exception->getTrace(),
                    ],
                    'message' => [
                        'body' => $message->body,
                        'contentEncoding' => $message->content_encoding,
                        'deliveryTag' => $message->getDeliveryTag(),
                        'consumerTag' => $message->getConsumerTag(),
                        'redelivered' => $message->isRedelivered(),
                        'exchange' => $message->getExchange(),
                        'queue' => $consumer->getQueue(),
                        'routingKey' => $message->getRoutingKey(),
                        'messageCount' => $message->getMessageCount(),
                        'properties' => $message->get_properties(),
                    ],
                    'handledAt' => now()->toIso8601String(),
                ], JSON_PRETTY_PRINT)),
                $consumer->getQueue() . '_error'
            ));
        } catch (AMQPChannelClosedException|AMQPConnectionClosedException) {
            $this->channel->getConnection()->reconnect();
            $this->handleException($consumer, $message, $exception);
        } catch (AMQPConnectionBlockedException) {
            do {
                sleep(1);
            } while ($this->channel->getConnection()->isBlocked());
            $this->handleException($consumer, $message, $exception);
        }
    }

    private function getPublisher(): Publisher
    {
        return app(Publisher::class);
    }

    private function log(string $message, array $data = []): void
    {
        Log::debug(get_class($this) . ':' . $message, $data);
    }
}
