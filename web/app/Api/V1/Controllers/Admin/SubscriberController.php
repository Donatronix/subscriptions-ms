<?php

namespace App\Api\V1\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class SubscriberController extends Controller
{
    /**
     *  Display a listing of the subscribers
     *
     * @OA\Get(
     *     path="/admin/subscribers",
     *     description="Get all subscribers",
     *     tags={"Subscribers"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "Subscriber",
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
     *                     property="total_subscribers",
     *                     type="integer",
     *                     description="Total count subscriber",
     *                     example=500,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_week",
     *                     type="integer",
     *                     description="number of new subscribers per week",
     *                     example=50,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_month",
     *                     type="integer",
     *                     description="number of new subscribers this month",
     *                     example=200,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_platform_week",
     *                     type="object",
     *                     description="number of new subscribers on this platform this week",
     *                     @OA\Property(
     *                        property="platform",
     *                        type="string",
     *                        description="Name of platform",
     *                        example="sumra chat",
     *                     ),
     *                     @OA\Property(
     *                        property="total",
     *                        type="integer",
     *                        description="number of new subscribers on this platform this week",
     *                        example=200,
     *                     ),
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_platform_month",
     *                     type="object",
     *                     description="number of new subscribers on this platform this month",
     *                     @OA\Property(
     *                        property="platform",
     *                        type="string",
     *                        description="Name of platform",
     *                        example="sumra chat",
     *                     ),
     *                     @OA\Property(
     *                        property="total",
     *                        type="integer",
     *                        description="number of new subscribers on this platform this month",
     *                        example=200,
     *                     ),
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
     *                 description="Subscriber parameter list",
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="Subscriber uuid",
     *                     example="9443407b-7eb8-4f21-8a5c-9614b4ec1bf9",
     *                 ),
     *                 @OA\Property(
     *                     property="username",
     *                     type="string",
     *                     description="Username",
     *                     example="Vasya",
     *                 ),
     *                 @OA\Property(
     *                     property="platform",
     *                     type="string",
     *                     description="Where the subscriber came from",
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
     *                  description="Uuid subscriber not found"
     *              ),
     *              @OA\Property(
     *                  property="username",
     *                  type="string",
     *                  description="Username not found"
     *              ),
     *              @OA\Property(
     *                  property="platform",
     *                  type="string",
     *                  description="Platform not found"
     *              ),
     *              @OA\Property(
     *                  property="total_subscribers",
     *                  type="string",
     *                  description="Total subscriber not found"
     *              ),
     *              @OA\Property(
     *                  property="new_subscribers_count_week",
     *                  type="string",
     *                  description="No new subscribers this week"
     *              ),
     *              @OA\Property(
     *                  property="new_subscribers_count_month",
     *                  type="string",
     *                  description="No new subscribers this month"
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
     * @param Request $request
     *
     * @return mixed
     */
    public function index(Request $request): mixed
    {
        try {
            $subscribers = Subscriber::paginate($request->get('limit', config('settings.pagination_limit')));

            return response()->jsonApi(
                array_merge([
                    'type' => 'success',
                    'title' => 'Operation was success',
                    'message' => 'The data was displayed successfully',
                    'general' => [
                        'total_subscribers' => $subscribers->count(),
                        'new_subscribers_count_week' => Subscriber::countNewSubscriberByTime('week')->get()->count(),
                        'new_subscribers_count_month' => Subscriber::countNewSubscriberByTime('month')->get()->count(),
                        'new_subscribers_count_platforms_week' => Subscriber::countNewSubscribersByPlatform('week')->get()->toArray(),
                        'new_subscribers_count_platforms_month' => Subscriber::countNewSubscribersByPlatform('month')->get()->toArray(),
//                        'total_earning' => 46.050,
                    ],
                ], $subscribers->toArray()),
                200);

        } catch (ModelNotFoundException $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Not operation",
                'message' => "Error showing all transactions",
                'data' => null,
            ], 404);
        } catch (Throwable $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Update failed",
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
    }

    /**
     *  Display a subscriber.
     *
     * @OA\Get(
     *     path="/admin/subscribers/{id}",
     *     description="Get subscriber by id",
     *     tags={"Subscribers"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "Subscriber",
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
     *                     property="total_subscribers",
     *                     type="integer",
     *                     description="Total count subscriber",
     *                     example=500,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_week",
     *                     type="integer",
     *                     description="number of new subscribers per week",
     *                     example=50,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_month",
     *                     type="integer",
     *                     description="number of new subscribers this month",
     *                     example=200,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_platform_week",
     *                     type="object",
     *                     description="number of new subscribers on this platform this week",
     *                     @OA\Property(
     *                        property="platform",
     *                        type="string",
     *                        description="Name of platform",
     *                        example="sumra chat",
     *                     ),
     *                     @OA\Property(
     *                        property="total",
     *                        type="integer",
     *                        description="number of new subscribers on this platform this week",
     *                        example=200,
     *                     ),
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_platform_month",
     *                     type="object",
     *                     description="number of new subscribers on this platform this month",
     *                     @OA\Property(
     *                        property="platform",
     *                        type="string",
     *                        description="Name of platform",
     *                        example="sumra chat",
     *                     ),
     *                     @OA\Property(
     *                        property="total",
     *                        type="integer",
     *                        description="number of new subscribers on this platform this month",
     *                        example=200,
     *                     ),
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
     *                 description="Subscriber parameter list",
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="Subscriber uuid",
     *                     example="9443407b-7eb8-4f21-8a5c-9614b4ec1bf9",
     *                 ),
     *                 @OA\Property(
     *                     property="username",
     *                     type="string",
     *                     description="Username",
     *                     example="Vasya",
     *                 ),
     *                 @OA\Property(
     *                     property="platform",
     *                     type="string",
     *                     description="Where the subscriber came from",
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
     *                  description="Uuid subscriber not found"
     *              ),
     *              @OA\Property(
     *                  property="username",
     *                  type="string",
     *                  description="Username not found"
     *              ),
     *              @OA\Property(
     *                  property="platform",
     *                  type="string",
     *                  description="Platform not found"
     *              ),
     *              @OA\Property(
     *                  property="total_subscribers",
     *                  type="string",
     *                  description="Total subscriber not found"
     *              ),
     *              @OA\Property(
     *                  property="new_subscribers_count_week",
     *                  type="string",
     *                  description="No new subscribers this week"
     *              ),
     *              @OA\Property(
     *                  property="new_subscribers_count_month",
     *                  type="string",
     *                  description="No new subscribers this month"
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
     * @param $id
     *
     * @return mixed
     */
    public function show($id): mixed
    {
        try {
            $subscriber = Subscriber::find($id);

            return response()->jsonApi(
                array_merge([
                    'type' => 'success',
                    'title' => 'Operation was success',
                    'message' => 'Subscriber was displayed successfully',
                    'general' => [
                        'total_subscribers' => Subscriber::count(),
                        'new_subscribers_count_week' => Subscriber::countNewSubscriberByTime('week')->get()->count(),
                        'new_subscribers_count_month' => Subscriber::countNewSubscriberByTime('month')->get()->count(),
                        'new_subscribers_count_platforms_week' => Subscriber::countNewSubscribersByPlatform('week')->get()->toArray(),
                        'new_subscribers_count_platforms_month' => Subscriber::countNewSubscribersByPlatform('month')->get()->toArray(),
//                        'total_earning' => 46.050,
                    ],
                ], $subscriber?->toArray() ?? []),
                200);

        } catch (ModelNotFoundException $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Not operation",
                'message' => "Subscriber does not exist",
                'data' => null,
            ], 404);
        } catch (Throwable $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Get subscriber failed",
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
    }

    /**
     *  Add new subscriber
     *
     * @OA\Post(
     *     path="/admin/subscribers",
     *     description="Add new subscriber",
     *     tags={"Subscribers"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "Subscriber",
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
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Subscriber user id",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="Subscriber username",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="platform",
     *         in="query",
     *         description="Platform subscriber is coming from",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
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
     *                     property="total_subscribers",
     *                     type="integer",
     *                     description="Total count subscriber",
     *                     example=500,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_week",
     *                     type="integer",
     *                     description="number of new subscribers per week",
     *                     example=50,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_month",
     *                     type="integer",
     *                     description="number of new subscribers this month",
     *                     example=200,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_platform_week",
     *                     type="object",
     *                     description="number of new subscribers on this platform this week",
     *                     @OA\Property(
     *                        property="platform",
     *                        type="string",
     *                        description="Name of platform",
     *                        example="sumra chat",
     *                     ),
     *                     @OA\Property(
     *                        property="total",
     *                        type="integer",
     *                        description="number of new subscribers on this platform this week",
     *                        example=200,
     *                     ),
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_platform_month",
     *                     type="object",
     *                     description="number of new subscribers on this platform this month",
     *                     @OA\Property(
     *                        property="platform",
     *                        type="string",
     *                        description="Name of platform",
     *                        example="sumra chat",
     *                     ),
     *                     @OA\Property(
     *                        property="total",
     *                        type="integer",
     *                        description="number of new subscribers on this platform this month",
     *                        example=200,
     *                     ),
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
     *                 description="Subscriber parameter list",
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="Subscriber uuid",
     *                     example="9443407b-7eb8-4f21-8a5c-9614b4ec1bf9",
     *                 ),
     *                 @OA\Property(
     *                     property="username",
     *                     type="string",
     *                     description="Username",
     *                     example="Vasya",
     *                 ),
     *                 @OA\Property(
     *                     property="platform",
     *                     type="string",
     *                     description="Where the subscriber came from",
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
     *                  description="Uuid subscriber not found"
     *              ),
     *              @OA\Property(
     *                  property="username",
     *                  type="string",
     *                  description="Username not found"
     *              ),
     *              @OA\Property(
     *                  property="platform",
     *                  type="string",
     *                  description="Platform not found"
     *              ),
     *              @OA\Property(
     *                  property="total_subscribers",
     *                  type="string",
     *                  description="Total subscriber not found"
     *              ),
     *              @OA\Property(
     *                  property="new_subscribers_count_week",
     *                  type="string",
     *                  description="No new subscribers this week"
     *              ),
     *              @OA\Property(
     *                  property="new_subscribers_count_month",
     *                  type="string",
     *                  description="No new subscribers this month"
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
     * @param Request $request
     *
     * @return mixed
     */
    public function store(Request $request): mixed
    {
        try {
            $subscriber = null;
            DB::transaction(function () use ($request, &$subscriber) {
                $validator = Validator::make($request->all(), [
                    'id' => 'required|string|max:255',
                    'username' => 'required|string|max:255',
                    'platform' => 'required|string|max:255',
                ]);

                if ($validator->fails()) {
                    return response()->jsonApi([
                        'type' => 'danger',
                        'title' => "Invalid data",
                        'message' => $validator->messages()->toArray(),
                        'data' => null,
                    ], 404);
                }

                // Retrieve the validated input...
                $validated = $validator->validated();


                if ($subscriber = Subscriber::find($validated['id'])) {
                    return response()->jsonApi([
                        'type' => 'danger',
                        'title' => "Adding new subscriber failed",
                        'message' => "Subscriber already exists",
                        'data' => null,
                    ], 404);
                }

                if ($subscriber = Subscriber::where('username', $validated['username'])->first()) {
                    return response()->jsonApi([
                        'type' => 'danger',
                        'title' => "Not operation",
                        'message' => "Username already in use",
                        'data' => null,
                    ], 404);
                }

                $subscriber = Subscriber::create($validated);
            });
        } catch (ModelNotFoundException $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Not operation",
                'message' => "Subscriber was not added. Please try again.",
                'data' => null,
            ], 404);
        } catch (Throwable $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Subscription failed",
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
        return response()->jsonApi([
            'type' => 'success',
            'title' => 'Operation was a success',
            'message' => 'Subscriber was added successfully',
            'general' => [
                'total_subscribers' => Subscriber::count(),
                'new_subscribers_count_week' => Subscriber::countNewSubscriberByTime('week')->get()->count(),
                'new_subscribers_count_month' => Subscriber::countNewSubscriberByTime('month')->get()->count(),
                'new_subscribers_count_platforms_week' => Subscriber::countNewSubscribersByPlatform('week')->get()->toArray(),
                'new_subscribers_count_platforms_month' => Subscriber::countNewSubscribersByPlatform('month')->get()->toArray(),
                //                    'total_earning' => 46.050,
            ],
            'data' => $subscriber->toArray(),
        ], 200);
    }

    /**
     *  Update subscriber record
     *
     * @OA\Put(
     *     path="/admin/subscribers/{id}",
     *     description="Update subscriber",
     *     tags={"Subscribers"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "Subscriber",
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
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Subscriber user id",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="Subscriber username",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="platform",
     *         in="query",
     *         description="Platform subscriber is coming from",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Subscriber user id",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
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
     *                     property="total_subscribers",
     *                     type="integer",
     *                     description="Total count subscriber",
     *                     example=500,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_week",
     *                     type="integer",
     *                     description="number of new subscribers per week",
     *                     example=50,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_month",
     *                     type="integer",
     *                     description="number of new subscribers this month",
     *                     example=200,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_platform_week",
     *                     type="object",
     *                     description="number of new subscribers on this platform this week",
     *                     @OA\Property(
     *                        property="platform",
     *                        type="string",
     *                        description="Name of platform",
     *                        example="sumra chat",
     *                     ),
     *                     @OA\Property(
     *                        property="total",
     *                        type="integer",
     *                        description="number of new subscribers on this platform this week",
     *                        example=200,
     *                     ),
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_platform_month",
     *                     type="object",
     *                     description="number of new subscribers on this platform this month",
     *                     @OA\Property(
     *                        property="platform",
     *                        type="string",
     *                        description="Name of platform",
     *                        example="sumra chat",
     *                     ),
     *                     @OA\Property(
     *                        property="total",
     *                        type="integer",
     *                        description="number of new subscribers on this platform this month",
     *                        example=200,
     *                     ),
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
     *                 description="Subscriber parameter list",
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="Subscriber uuid",
     *                     example="9443407b-7eb8-4f21-8a5c-9614b4ec1bf9",
     *                 ),
     *                 @OA\Property(
     *                     property="username",
     *                     type="string",
     *                     description="Username",
     *                     example="Vasya",
     *                 ),
     *                 @OA\Property(
     *                     property="platform",
     *                     type="string",
     *                     description="Where the subscriber came from",
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
     *                  description="Uuid subscriber not found"
     *              ),
     *              @OA\Property(
     *                  property="username",
     *                  type="string",
     *                  description="Username not found"
     *              ),
     *              @OA\Property(
     *                  property="platform",
     *                  type="string",
     *                  description="Platform not found"
     *              ),
     *              @OA\Property(
     *                  property="total_subscribers",
     *                  type="string",
     *                  description="Total subscriber not found"
     *              ),
     *              @OA\Property(
     *                  property="new_subscribers_count_week",
     *                  type="string",
     *                  description="No new subscribers this week"
     *              ),
     *              @OA\Property(
     *                  property="new_subscribers_count_month",
     *                  type="string",
     *                  description="No new subscribers this month"
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
     * @param Request $request
     * @param         $id
     *
     * @return mixed
     */
    public function update(Request $request, $id): mixed
    {
        try {
            $subscriber = null;
            DB::transaction(function () use ($request, $id, &$subscriber) {
                $validator = Validator::make($request->all(), [
                    'username' => 'required|string|max:255',
                    'platform' => 'required|string|max:255',
                ]);

                if ($validator->fails()) {
                    return response()->jsonApi([
                        'type' => 'danger',
                        'title' => "Not operation",
                        'message' => $validator->messages()->toArray(),
                        'data' => null,
                    ], 404);
                }

                // Retrieve the validated input...
                $validated = $validator->validated();

                if (Subscriber::where('username', $validated['username'])->first() != Subscriber::find($id)) {
                    return response()->jsonApi([
                        'type' => 'danger',
                        'title' => "Not operation",
                        'message' => "Username already in use",
                        'data' => null,
                    ], 404);
                }

                $subscriber = Subscriber::find($id);

                $subscriber->update($validated);

            });
        } catch (ModelNotFoundException $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Update failed",
                'message' => "Subscriber does not exist",
                'data' => null,
            ], 404);
        } catch (Throwable $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Update failed",
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
        return response()->jsonApi([
            'type' => 'success',
            'title' => 'Operation was a success',
            'message' => 'Subscriber was updated successfully',
            'general' => [
                'total_subscribers' => Subscriber::count(),
                'new_subscribers_count_week' => Subscriber::countNewSubscriberByTime('week')->get()->count(),
                'new_subscribers_count_month' => Subscriber::countNewSubscriberByTime('month')->get()->count(),
                'new_subscribers_count_platforms_week' => Subscriber::countNewSubscribersByPlatform('week')->get()->toArray(),
                'new_subscribers_count_platforms_month' => Subscriber::countNewSubscribersByPlatform('month')->get()->toArray(),
                //                    'total_earning' => 46.050,
            ],
            'data' => $subscriber->toArray(),
        ], 200);
    }

    /**
     *  Delete subscriber record
     *
     * @OA\Delete(
     *     path="/admin/subscribers/{id}",
     *     description="Delete subscriber",
     *     tags={"Subscribers"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "Subscriber",
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
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Subscriber user id",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
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
     *                     property="total_subscribers",
     *                     type="integer",
     *                     description="Total count subscriber",
     *                     example=500,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_week",
     *                     type="integer",
     *                     description="number of new subscribers per week",
     *                     example=50,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_month",
     *                     type="integer",
     *                     description="number of new subscribers this month",
     *                     example=200,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_platform_week",
     *                     type="object",
     *                     description="number of new subscribers on this platform this week",
     *                     @OA\Property(
     *                        property="platform",
     *                        type="string",
     *                        description="Name of platform",
     *                        example="sumra chat",
     *                     ),
     *                     @OA\Property(
     *                        property="total",
     *                        type="integer",
     *                        description="number of new subscribers on this platform this week",
     *                        example=200,
     *                     ),
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_platform_month",
     *                     type="object",
     *                     description="number of new subscribers on this platform this month",
     *                     @OA\Property(
     *                        property="platform",
     *                        type="string",
     *                        description="Name of platform",
     *                        example="sumra chat",
     *                     ),
     *                     @OA\Property(
     *                        property="total",
     *                        type="integer",
     *                        description="number of new subscribers on this platform this month",
     *                        example=200,
     *                     ),
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
     *                 description="Subscriber parameter list",
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="Subscriber uuid",
     *                     example="9443407b-7eb8-4f21-8a5c-9614b4ec1bf9",
     *                 ),
     *                 @OA\Property(
     *                     property="username",
     *                     type="string",
     *                     description="Username",
     *                     example="Vasya",
     *                 ),
     *                 @OA\Property(
     *                     property="platform",
     *                     type="string",
     *                     description="Where the subscriber came from",
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
     *                  description="Uuid subscriber not found"
     *              ),
     *              @OA\Property(
     *                  property="username",
     *                  type="string",
     *                  description="Username not found"
     *              ),
     *              @OA\Property(
     *                  property="platform",
     *                  type="string",
     *                  description="Platform not found"
     *              ),
     *              @OA\Property(
     *                  property="total_subscribers",
     *                  type="string",
     *                  description="Total subscriber not found"
     *              ),
     *              @OA\Property(
     *                  property="new_subscribers_count_week",
     *                  type="string",
     *                  description="No new subscribers this week"
     *              ),
     *              @OA\Property(
     *                  property="new_subscribers_count_month",
     *                  type="string",
     *                  description="No new subscribers this month"
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
     * @param $id
     *
     * @return mixed
     */
    public function destroy($id): mixed
    {
        try {
            $subscribers = null;
            DB::transaction(function () use ($id, &$subscribers) {
                $subscriber = Subscriber::find($id);

                $subscriber->delete();

                $subscribers = Subscriber::paginate(config('settings.pagination_limit'));

            });
        } catch (ModelNotFoundException $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Delete failed",
                'message' => "Subscriber does not exist",
                'data' => null,
            ], 404);
        } catch (Throwable $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Delete failed",
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
        return response()->jsonApi([
            'type' => 'success',
            'title' => 'Operation was a success',
            'message' => 'Subscriber was deleted successfully',
            'general' => [
                'total_subscribers' => Subscriber::count(),
                'new_subscribers_count_week' => Subscriber::countNewSubscriberByTime('week')->get()->count(),
                'new_subscribers_count_month' => Subscriber::countNewSubscriberByTime('month')->get()->count(),
                'new_subscribers_count_platforms_week' => Subscriber::countNewSubscribersByPlatform('week')->get()->toArray(),
                'new_subscribers_count_platforms_month' => Subscriber::countNewSubscribersByPlatform('month')->get()->toArray(),
                //                    'total_earning' => 46.050,
            ],
            'data' => $subscribers->toArray(),
        ], 200);
    }

}
