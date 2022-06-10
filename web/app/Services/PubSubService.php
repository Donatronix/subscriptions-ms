<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class PubSubService
{
    public static function Publisher($payload)
    {
    
    $data = json_encode($payload, true);
    $connection = new AMQPStreamConnection(env('AMQP_HOST'), env('AMQP_PORT'), env('AMQP_USER'), env('AMQP_PASSORD'));
    $channel = $connection->channel();

    if (empty($data)) {
        $data = "info: Hello World!";
    }
    $msg = new AMQPMessage($data);

    $channel->basic_publish($msg, env('AMQP_EXCHANGE_NAME'), env('AMQP_QUEUE_NAME'));
    
    $channel->close();
    $connection->close();
        
    }
}
