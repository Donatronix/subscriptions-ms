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
            $total_users = User::getTotalUsers();
            $new_users_count_week = User::getCountNewUserByTime('week');
            $new_users_count_month = User::getCountNewUserByTime('week');
            $total_earning = 46.050;
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
