<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class PubSubService
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $payload)
    {
        $this->inputData = $payload;
    }

     /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    $data = json_encode($this->inputData, true);
    $connection = new AMQPStreamConnection(
        env('PUB_HOST'),
        env('PUB_PORT'),
        env('PUB_USER'),
        env('PUB_PASSWORD'),
        env('PUB_VHOST'),
    );

    $channel = $connection->channel();

    if (empty($data)) {
        $data = "info: Hello World!";
    }
    $msg = new AMQPMessage(
        $data, 
        array('delivery_mode' => 2) # make message persistent, so it is not lost if server crashes or quits
    );

    $channel->basic_publish(
        $msg,
        env('PUB_EXCHANGE_NAME'),
        env('PUB_QUEUE_NAME'),
    );
    
    $channel->close();
    $connection->close();
        
    }
}