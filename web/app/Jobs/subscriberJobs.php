<?php

namespace App\Jobs;

use App\Models\SubMgsId;
use App\Models\WaitingListMS as ModelsWaitingListMS;
use Sumra\SDK\Facades\PubSub;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class subscriberJobs implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private const RECEIVER_LISTENER = 'waitingListMS';
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $inputData)
    {
        $this->inputData = $inputData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $inputData = $this->inputData;
        dd($this->inputData);
        $request = new \Illuminate\Http\Request($inputData);

        $validation = Validator::make($request->all(), [
            'platform' => 'string|required',
        ]);

        if ($validation->fails()) {
            PubSub::transaction(function () {})->publish(self::RECEIVER_LISTENER, [
                'status' => 'error',
                'subsriber_id' => $inputData['platform'],
                'message_id' => $inputData['message_id'],
                'message' => $validation->errors()
            ], "waitingLinst");
            return true;
        }
        
        $inputData = (object)$request->all();
        // dd($inputData->platform);
        // Write log
        try {
            $waitListMs = SubMgsId::create([
                'message_id' => $inputData->message_id,
                'subsriber_id' => $inputData->subscriber_id,
            ]);
            if (!$waitListMs) {
                $waitListMs = SubMgsId::create([
                    'message_id' => $inputData->message_id,
                    'subsriber_id' => $inputData->subscriber_id,
                    'status' => "Failed",
                ]);
    
                Log::info("WiatiList Message Failed");
                exit;
            } else {
                // Return result
                PubSub::transaction(function () {
                })->publish(self::RECEIVER_LISTENER, [
                    'type' => 'success',
                    'title' => "WitingList Message Sent",
                    'data' => [
                        "subscriber_id" => $inputData->subsriber_id,
                        "message_id" => $inputData->message_id,
                    ]
                ], "waitingLinst");
                return true;
            }
        } catch (\Exception $e) {
            Log::info('Log of waiting list message failed: ' . $e->getMessage());
        }
    }
}
