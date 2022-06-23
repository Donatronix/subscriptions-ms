<?php

namespace App\Api\V1\Controllers\Admin;

use App\Api\V1\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionItem;
use App\Models\SubscriptionChannel;
use App\Models\SubscriptionParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PubSub;

class SubscriptionController extends Controller
{
    /**
     *  Display a listing of subscriptions
     *
     * @OA\Get(
     *     path="/subscriptions",
     *     description="All subscription",
     *     tags={"Subscriptions"},
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
     *
     *     @OA\Response(
     *         response="200",
     *         description="Output data",
     *
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="subscriptions parameter list",
     *                 @OA\Property(
     *                     property="subscription",
     *                     type="string",
     *                     description="subscriptions",
     *                     example="All new subscriptions",
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
     *                  property="subscriptions",
     *                  type="string",
     *                  description="No data to display"
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
    public function index(Request $request)
    {
        try {
            $subscriptions = Subscription::with(['parameters', 'items', 'channels'])
                ->where('user_id', Auth::user()->getAuthIdentifier())
                ->get()->toArray();

            return response()->jsonApi($subscriptions, 200);
        } catch (\Exception $e) {
            return response()->jsonApi([
                'type' => 'error',
                'title' => 'Exception',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

     /**
     *  Add new subscription
     *
     * @OA\Post(
     *     path="/subscription",
     *     description="Add new user subscription",
     *     tags={"Subscriptions"},
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
     *         description="subscription user id",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Subscription type",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="item_id[]",
     *         in="query",
     *         description="Subscription items",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     * 
     *      @OA\Parameter(
     *         name="object[]",
     *         in="query",
     *         description="Subscription objects",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="channel",
     *         in="query",
     *         description="Channel subscription is using to signup",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     * 
     *    @OA\Parameter(
     *         name="parameter[]",
     *         in="query",
     *         description="Subscription parameter",
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
     *                     property="item_id[]",
     *                     type="string",
     *                     description="Subscription Item",
     *                     example="advert",
     *                 ),
     *                 @OA\Property(
     *                     property="object[]",
     *                     type="string",
     *                     description="subscription Object",
     *                     example="advert",
     *                 ),
     *                 @OA\Property(
     *                     property="channel",
     *                     type="string",
     *                     description="Subscription Channel",
     *                     example="Discord",
     *                 ),
     *                 @OA\Property(
     *                     property="parameter",
     *                     type="string",
     *                     description="Subscription Parameter",
     *                     example="Advert",
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
     *                  description="Uuid subscription not found"
     *              ),
     *              @OA\Property(
     *                  property="username",
     *                  type="string",
     *                  description="Username not found"
     *              ),
     *              @OA\Property(
     *                  property="item",
     *                  type="string",
     *                  description="item not found"
     *              ),
     *              @OA\Property(
     *                  property="channel",
     *                  type="string",
     *                  description="Channel not found"
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        /**
         * Save income data
         */
        try {
            // Get data
            $data = $this->dataValidate($request);

            // Create subscription
            $subscription = Subscription::create([
                'user_id' => $data->userId,
                'type' => $data->type,
                'period' => $request->get('period', Subscription::PERIOD_DAILY),
                'status' => $request->get('status', Subscription::STATUS_NOT_ACTIVE)
            ]);

            // Associate by type
            switch ($data->type) {
                case 'item':
                    SubscriptionItem::create([
                        'subscription_id' => $subscription->id,
                        'item_id' => $data->item['id'],
                        'item_object' => $data->item['object']
                    ]);

                    break;
                case 'search':
                    //
                    break;
                default:
                    break;
            }

            // Save search parameters
            if($data->parameters && !empty($data->parameters)) {
                // Add new search parameters
                foreach ($data->parameters as $parameter) {
                    if(empty($parameter->key) || empty($parameter->value)){
                        continue;
                    }

                    SubscriptionParameter::create([
                        'subscription_id' => $subscription->id,
                        'key' => $parameter->key,
                        'value' => $parameter->value
                    ]);
                }
            }

            // We associate on which channel mailing should be performed on this subscription
            if($data->channels && !empty($data->channels)){
                foreach ($data->channels as $value) {
                    SubscriptionChannel::create([
                        'subscription_id' => $subscription->id,
                        'channel' => $value
                    ]);
                }
            }

            // Return response
            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Save Subscription',
                'message' => "Subscription has been successful saved",
                'data' => $subscription
            ], 200);
        } catch (\Exception $e) {
            return response()->jsonApi([
                'type' => 'error',
                'title' => 'Exception',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

     /**
     *  Update subscription record
     *
     * @OA\Put(
     *     path="/subscription/{id}",
     *     description="Update subscription",
     *     tags={"Subscriptions"},
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
     *         description="subscription id",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
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
     *                     property="item_id[]",
     *                     type="string",
     *                     description="Subscription Item",
     *                     example="advert",
     *                 ),
     *                 @OA\Property(
     *                     property="object[]",
     *                     type="string",
     *                     description="subscription Object",
     *                     example="advert",
     *                 ),
     *                 @OA\Property(
     *                     property="channel",
     *                     type="string",
     *                     description="Subscription Channel",
     *                     example="Discord",
     *                 ),
     *                 @OA\Property(
     *                     property="parameter",
     *                     type="string",
     *                     description="Subscription Parameter",
     *                     example="Advert",
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
     *                  description="Uuid subscription not found"
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
     * @return \Sumra\JsonApi\
     */
    public function update(Request $request, $id)
    {
        /**
         * Save income data
         */
        try {
            $data = $this->dataValidate($request);

            // Create subscription
            $subscription = Subscription::find($id);
            $subscription->type = $data->type;
            $subscription->period = $request->get('period', Subscription::PERIOD_DAILY);
            $subscription->status = $request->get('status', Subscription::STATUS_NOT_ACTIVE);
            $subscription->save();

            // Associate by type
            switch ($data->type) {
                case 'item':
                    // Delete parameters
                    SubscriptionItem::where('subscription_id', $subscription->id)->delete();

                    // add new
                    SubscriptionItem::create([
                        'subscription_id' => $subscription->id,
                        'item_id' => $data->item['id'],
                        'item_object' => $data->item['object']
                    ]);

                    break;
                case 'search':
                    //
                    break;
                default:
                    break;
            }

            // Save search parameters
            if($data->parameters && !empty($data->parameters)) {
                // Delete parameters
                SubscriptionParameter::where('subscription_id', $subscription->id)->delete();

                // Add new parameters
                foreach ($data->parameters as $parameter) {
                    if(empty($parameter->key) || empty($parameter->value)){
                        continue;
                    }

                    SubscriptionParameter::create([
                        'subscription_id' => $subscription->id,
                        'key' => $parameter->key,
                        'value' => $parameter->value
                    ]);
                }
            }

            // We associate on which channel mailing should be performed on this subscription
            if($data->channels && !empty($data->channels)){
                // Delete sender channels
                SubscriptionChannel::where('subscription_id', $subscription->id)->delete();

                // Add new channels
                foreach ($data->channels as $value) {
                    SubscriptionChannel::create([
                        'subscription_id' => $subscription->id,
                        'channel' => $value
                    ]);
                }
            }

            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Update Subscription',
                'message' => "Subscription has been successful updated"
            ], 200);
        } catch (\Exception $e) {
            return response()->jsonApi([
                'type' => 'error',
                'title' => 'Exception',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }


     /**
     *  Delete subscription record
     *
     * @OA\Delete(
     *     path="/delete/subscription/{id}",
     *     description="Delete subscription",
     *     tags={"Subscriptions"},
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
     *         description="subscription id",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="204",
     *         description="Output data",
     *
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="general",
     *                 type="object",
     *                 description="Description of general parameters",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     description="subscription id",
     *                     example=500,
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
     *                  description="Uuid not found"
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
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        // Try delete
        try {
            $subscription = Subscription::find($id);

            if (!$subscription) {
                return response()->jsonApi([
                    'type' => 'error',
                    'title' => 'Subscription not found',
                    'message' => "Subscription #{$id} not found"
                ], 404);
            }

            $array = [
                'user_id' => Auth::user()->getAuthIdentifier(),
                'subscription' => $subscription->toArray(),
                'subject' => "Subscription with id: ' . $id . ' was deleted"
            ];
            PubSub::transaction(function() use ($subscription) {
                //Delete links with services
                SubscriptionItem::where('subscription_id', $subscription->id)->delete();

                // Delete sender channels
                SubscriptionChannel::where('subscription_id', $subscription->id)->delete();

                // Delete parameters
                SubscriptionParameter::where('subscription_id', $subscription->id)->delete();

                // Delete subscription
                $subscription->delete();
            })->publish('subscriptionDelete', $array, 'Subscriber');

            // Return response
            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Destroy subscription',
                'message' => 'Subscription with id: ' . $id . ' was deleted'
            ], 204);
        } catch (\Exception $e) {
            return response()->jsonApi([
                'type' => 'error',
                'title' => 'Exception',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    /**
     *  Update subscription status
     *
     * @OA\Put(
     *     path="/subscription/{id}/status",
     *     description="Update a subscription status",
     *     tags={"Subscriptions"},
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
     *         description="subscription id",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
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
     *                     property="item_id[]",
     *                     type="string",
     *                     description="Subscription Item",
     *                     example="advert",
     *                 ),
     *                 @OA\Property(
     *                     property="object[]",
     *                     type="string",
     *                     description="subscription Object",
     *                     example="advert",
     *                 ),
     *                 @OA\Property(
     *                     property="channel",
     *                     type="string",
     *                     description="Subscription Channel",
     *                     example="Discord",
     *                 ),
     *                 @OA\Property(
     *                     property="parameter",
     *                     type="string",
     *                     description="Subscription Parameter",
     *                     example="Advert",
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
     *                  description="Uuid not found"
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
     * 
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Sumra\JsonApi\
     */
    public function status(Request $request, $id)
    {
        $rules = [
            'status' => 'integer'
        ];

        $this->validate($request, $rules);

        $subscription = Subscription::find($id);
        $subscription->status = $request->get('status', Subscription::STATUS_NOT_ACTIVE);
        $subscription->save();

        return response()->jsonApi([
            'type' => 'success',
            'title' => 'Status update',
            'message' => "Status has been successful updated",
            'data' => $subscription
        ], 200);
    }


    /**
     * @param Request $request
     *
     * @return object
     */
    private function dataValidate(Request $request){
        /**
         * Validate request
         */
        $validator = Validator::make($request->all(), [
            'item_id' => 'integer|nullable',
            'parameters' => 'array|nullable',
            'channels' => 'array|nullable'
        ]);

        if ($validator->fails()) {
            return response()->jsonApi([
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get data
        $data = (object) $validator->validate();
        $data->userId = Auth::user()->getAuthIdentifier();

        // Return data
        return $data;
    }
}
