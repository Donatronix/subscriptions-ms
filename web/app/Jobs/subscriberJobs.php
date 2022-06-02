<?php

namespace App\Jobs;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class subscriberJobs implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $message;
    private $url;
    private $channel;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message, $url = "", $channel = "")
    {
        $this->message = $message;
        $this->channel = $channel;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payload = [
            "message body" => $this->message,
            "platform-uri" => $this->url
        ];
        echo 'Event: Subscribers Created' . PHP_EOL;
        echo json_encode($payload) . PHP_EOL; 
    }
}
