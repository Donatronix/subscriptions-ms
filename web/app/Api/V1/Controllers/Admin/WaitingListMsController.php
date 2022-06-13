<?php

namespace App\Api\V1\Controllers\Admin;

use App\Models\WaitingListMS;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WaitingListMsController extends Controller
{
    public function index()
    {
        $waitingListMs = WaitingListMS::with('submgId.subscribe')->all();
        return response()->json($waitingListMs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'platform' => 'required',
            'message' => 'required',
        ]);
        $message = WishListMS::create([
            'message' => $request->message,
        ]);

        $message_id = $message->id;

        if (is_array($request->platform) && count($request->platform) > 1) {

            foreach ($request->platform as $platform) {
                $subscribers = Subscriber::where('platform', $platform)->get(['username', 'id']);
                foreach ($subscribers as $sub) {
                    $data = [
                        'title' => $request->title,
                        "platform" => $platform,
                        "subscriber_id" => $sub->id,
                        "subscriber" => $sub->username,
                        "waiting_list_ms_id" => $message_id,
                        "product_url" => $request->url
                    ];
                    dispatch(new WaitingListMS($data));
                }
            }
        } else {
            $subscribers = Subscriber::where('platform', $request->platform)->get(['username', 'id']);
            foreach ($subscribers as $sub) {
                $data = [
                    'title' => $request->title,
                    "platform" => $request->platform,
                    "subscriber_id" => $sub->id,
                    "subscriber" => $sub->username,
                    "waiting_list_ms_id" => $message_id,
                    "product_url" => $request->url
                ];
                dispatch(new WaitingListMS($data));
            }
        }

    }
}
