<?php

namespace App\Listeners;

use App\Models\SubMgsId;
use App\Models\WaitingListMS as ModelsWaitingListMS;
use Sumra\SDK\Facades\PubSub;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
// use PubSub;

class WaitingListMS
{
    public $inputData;
    /**
     * @var string
     */
    private const RECEIVER_LISTENER = 'waitingListMS';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(array $inputData)
    {
        $this->inputData = $inputData;
    }

    /**
     * Handle the event.
     *
     * @param  WaitingList $event
     * @return void
     */
    public function handle()
    {
        $inputData = $this->inputData;
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
            Log::info('Waiting list message failed: ' . $e->getMessage());
        }
    }
}
