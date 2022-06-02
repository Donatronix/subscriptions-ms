<?php

namespace App\Console\Commands;

use App\Jobs\subscriberJobs;
use App\Models\Subscriber;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Console\Command;

class pingJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ping:Pingjob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $lists = Subscriber::all();
        $proLink ="https://discord.gg/DUMwfyckKy";
        dispatch(new subscriberJobs($lists, $proLink))->onQueue('waitingLinst');
        // ...
    }
}
