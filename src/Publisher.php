<?php

namespace SMSkin\LaravelRabbitMq;

use Illuminate\Support\Collection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPChannelClosedException;
use PhpAmqpLib\Exception\AMQPConnectionBlockedException;
use PhpAmqpLib\Exception\AMQPConnectionClosedException;
use SMSkin\LaravelRabbitMq\Entities\Message;

class Publisher
{
    public function __construct(private readonly AMQPStreamConnection $connection)
    {
    }

    /**
     * @throws AMQPChannelClosedException
     * @throws AMQPConnectionClosedException
     * @throws AMQPConnectionBlockedException
     */
    public function publish(Message $message): void
    {
        $this->connection->channel()->basic_publish(
            $message->message,
            $message->exchangeName,
            $message->routingKey,
            $message->mandatory,
            $message->immediate,
            $message->ticket
        );
    }

    /**
     * @param Collection<Message> $messages
     * @throws AMQPChannelClosedException
     * @throws AMQPConnectionClosedException
     * @throws AMQPConnectionBlockedException
     */
    public function batchPublish(Collection $messages): void
    {
        if ($messages->isEmpty()) {
            return;
        }

        $messages->each(function (Message $message) {
            $this->connection->channel()->batch_basic_publish(
                $message->message,
                $message->exchangeName,
                $message->routingKey,
                $message->mandatory,
                $message->immediate,
                $message->ticket
            );
        });

        $this->connection->channel()->publish_batch();
    }
}
