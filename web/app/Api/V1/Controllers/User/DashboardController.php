<?php

namespace App\Api\V1\Controllers\User;

use App\Api\V1\Controllers\Controller;
use App\Models\Subscriber;
use App\Traits\SubscribersAnalysisTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Throwable;

class DashboardController extends Controller
{
    /**
     *  Display a listing of the subscribers
     *
     * @OA\Get(
     *     path="/user/dashboard",
     *     description="Get all subscribers dashboard",
     *     tags={"User | Dashboard"},
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
     *                     property="new_subscribers_by_week_count",
     *                     type="integer",
     *                     description="number of new subscribers per week",
     *                     example=50,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_by_month_count",
     *                     type="integer",
     *                     description="number of new subscribers this month",
     *                     example=200,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_by_year_count",
     *                     type="integer",
     *                     description="number of new subscribers this year",
     *                     example=200,
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_by_platforms_per_week",
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
     *                     property="new_subscribers_count_channel_week",
     *                     type="object",
     *                     description="number of new subscribers on this channel this week",
     *                     @OA\Property(
     *                        property="channel",
     *                        type="string",
     *                        description="Name of channel",
     *                        example="WhatsApp",
     *                     ),
     *                     @OA\Property(
     *                        property="total",
     *                        type="integer",
     *                        description="number of new subscribers on this channel this week",
     *                        example=200,
     *                     ),
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_channel_month",
     *                     type="object",
     *                     description="number of new subscribers on this channel this month",
     *                     @OA\Property(
     *                        property="channel",
     *                        type="string",
     *                        description="Name of channel",
     *                        example="WhatsApp",
     *                     ),
     *                     @OA\Property(
     *                        property="total",
     *                        type="integer",
     *                        description="number of new subscribers on this channel this month",
     *                        example=200,
     *                     ),
     *                 ),
     *                 @OA\Property(
     *                     property="new_subscribers_count_channel_year",
     *                     type="object",
     *                     description="number of new subscribers on this channel this year",
     *                     @OA\Property(
     *                        property="channel",
     *                        type="string",
     *                        description="Name of channel",
     *                        example="WhatsApp",
     *                     ),
     *                     @OA\Property(
     *                        property="total",
     *                        type="integer",
     *                        description="number of new subscribers on this channel this year",
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
            $user_id = Auth::user()->id;

            $statistics = SubscribersAnalysisTrait::getSubscribersStatistics();

            $response = Http::retry(3, 100)->withHeaders([
                'app-id' => config('settings.api.app_id'),
                'user_id' => $user_id,
            ])->get(config('settings.api.referrals_ms') . '/v1/admin/total-earnings');

            $totalEarnings = floatVal($response->json('data'));

            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Operation was success',
                'message' => 'The data was displayed successfully',
                'general' => [
                    'total_subscribers' => Subscriber::query()->count(),
                    $statistics,
                    'total_earning' => $totalEarnings,
                ],
                'data' => Subscriber::find($user_id),
            ], 200);
        } catch (Throwable $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Get subscriber dashboard failed",
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
    }
}