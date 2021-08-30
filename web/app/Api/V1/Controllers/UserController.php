<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{

    public function index()
    {
        try {
            $users_info = User::all();
            dd($users_info);

            return response()->jsonApi([
                'type' => 'success',
                'title' => "Showing all transactions",
                'message' => 'Transactions are shown successfully',
                'data' => $users_info,
            ], 200);
        }
        catch (ModelNotFoundException $e){
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Not operation",
                'message' => "Error showing all transactions",
                'data' => null
            ], 404);
        }
    }
}
