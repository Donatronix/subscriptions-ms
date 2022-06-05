<?php

namespace App\Http\Controllers;

use App\Listeners\WaitingListMS;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class testController extends Controller
{
    //
    public function storeTest(Request $request): mixed
    {
        $subscribers = Subscriber::where('platform', $request->platform)->get(['username','id']);
       
        foreach($subscribers as $sub){
            $data = [
                "platform" => $request->platform,
                "subscriber_id" => $sub->id,
                "subscriber" => $sub->username,
                "message_id" =>$request->message_id,
                "product_url" =>$request->url
            ];
            
            dispatch(new WaitingListMS($data));
           return print_r("sent successfully to database: "); 
        }
    }
}
