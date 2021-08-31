<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\DataService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     *  Display a listing of the users
     *
     *  @OA\Get(
     *     path="/v1/waiting-lists/",
     *     description="Get all users",
     *     tags={"Users"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "User",
     *              "ManagerWrite"
     *          },
     *     }},
     *
     *     x={
     *          "auth-type": "Applecation & Application Use",
     *          "throttling-tier": "Unlimited",
     *          "wso2-appliocation-security": {
     *              "security-types": {"oauth2"},
     *              "optional": "false"
     *           },
     *     },
     *
     *     @OA\Response(
     *         response="200",
     *         description="Output data",
     *
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="general",
     *                 type="object",
     *                 description="Description of general parameters",
     *                 @OA\Property(
     *                     property="total_users",
     *                     type="integer",
     *                     description="Total count user",
     *                     example=500,
     *                 ),
     *                 @OA\Property(
     *                     property="new_users_count_week",
     *                     type="integer",
     *                     description="number of new users per week",
     *                     example=50,
     *                 ),
     *                 @OA\Property(
     *                     property="new_users_count_month",
     *                     type="integer",
     *                     description="number of new users this month",
     *                     example=200,
     *                 ),
     *                 @OA\Property(
     *                     property="total_earning",
     *                     type="double",
     *                     description="the total earnings of these ochlomons",
     *                     example=50.50,
     *                 ),
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="User parameter list",
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="User uuid",
     *                     example="9443407b-7eb8-4f21-8a5c-9614b4ec1bf9",
     *                 ),
     *                 @OA\Property(
     *                     property="username",
     *                     type="string",
     *                     description="Username",
     *                     example="Vasya",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="User email",
     *                     example="kassulke.adonis@example.org",
     *                 ),
     *                 @OA\Property(
     *                     property="platform",
     *                     type="string",
     *                     description="Where the user came from",
     *                     example="sumra chat",
     *                 ),
     *             ),
     *         ),
     *     ),
     *
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *
     *     @OA\Response(
     *          response="404",
     *          description="Not found",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="id",
     *                  type="string",
     *                  description="Uuid user not found"
     *              ),
     *              @OA\Property(
     *                  property="username",
     *                  type="string",
     *                  description="Username not found"
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  description="Email not found"
     *              ),
     *              @OA\Property(
     *                  property="platform",
     *                  type="string",
     *                  description="Platform not found"
     *              ),
     *              @OA\Property(
     *                  property="total_users",
     *                  type="string",
     *                  description="Total user not found"
     *              ),
     *              @OA\Property(
     *                  property="new_users_count_week",
     *                  type="string",
     *                  description="No new users this week"
     *              ),
     *              @OA\Property(
     *                  property="new_users_count_month",
     *                  type="string",
     *                  description="No new users this month"
     *              ),
     *              @OA\Property(
     *                  property="total_earning",
     *                  type="string",
     *                  description="No total earnings information found"
     *              ),
     *          ),
     *     ),
     *
     *     @OA\Response(
     *         response="500",
     *         description="Unknown error"
     *     ),
     * )
     *
     *  @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $users = User::paginate($request->get('limit', config('settings.pagination_limit')));

            return response()->jsonApi(
                array_merge([
                    'type' => 'success',
                    'title' => 'Operation was success',
                    'message' => 'The data was displayed successfully',
                    'general' => [
                        'total_users' => $users->count(),
                        'new_users_count_week' => User::countNewUserByTime('week')->count(),
                        'new_users_count_month' => User::countNewUserByTime('month')->count(),
                        'total_earning' => 46.050,
                    ]
                ], $users->toArray()),
                200);

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
