<?php

namespace App\Api\V1\Controllers\Application;

use App\Api\V1\Controllers\Controller;
use App\Models\Subscriber;
use App\Traits\SubscribersAnalysisTrait;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Sumra\SDK\Enums\MicroservicesEnums;
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
     *         response="400",
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

            $response = Http::retry(3, 100)
                ->withHeaders([
                    'app-id' => config('settings.app_id'),
                    'user_id' => $user_id,
                ])
                ->get(config('settings.api.referrals') . '/webhooks/total-earnings');

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

    /**
     *  Get Balance summary for user for user
     *
     * @OA\Get(
     *     path="/invited-users/{id}",
     *     description="A list of leaders in the invitation referrals",
     *     tags={"Balance Summary"},
     *
     *     security={{
     *         "default" :{
     *             "ManagerRead",
     *             "User",
     *             "ManagerWrite"
     *         }
     *     }},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="invited referrals",
     *
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                      property="overview_earnings",
     *                      type="string",
     *                      description="total earnings per platform and number of users",
     *                      example=450000,
     *                 ),
     *                  @OA\Property(
     *                      property="subTotalPlatformInvitedUsers",
     *                      type="integer",
     *                      description="Subtotal of number of platform users",
     *                      example="300",
     *                 ),
     *                 @OA\Property(
     *                      property="subTotalEarnings",
     *                      type="string",
     *                      description="Total earnings on all platforms",
     *                      example="WhatsApp",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="User not found",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Unknown error"
     *     ),
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getEarningsOverview(Request $request)
    {
        try {
            $id = Auth::user()->getAuthIdentifier() ?? '96a64f64-3a29-43df-87b8-1357fd0a9256';

            $response = Http::retry(3, 100, function ($exception, $request) {
                return $exception instanceof ConnectionException;
            })->withHeaders([
                'app-id' => MicroservicesEnums::REFERRALS_MS,
                'user-id' => $id,
            ])->get(config('settings.api.referrals') . '/webhooks/leaderboard/overview-earnings/' . $id);

            $balance_summary = null;
            if (!$response instanceof ConnectionException) {
                $balance_summary = $response->json('data');
            }

            return response()->jsonApi([
                'type' => 'success',
                'title' => "Get subscriber dashboard succeeded",
                'data' => $balance_summary,
            ]);
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
