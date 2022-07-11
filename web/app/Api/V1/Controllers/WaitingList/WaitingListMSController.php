<?php

namespace App\Http\Controllers;

use App\Jobs\PingJob;
use App\Models\SubMgsId;
use App\Models\WaitingListMS;
use App\Services\PubSubService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class WaitingListMSController extends Controller
{

    /**
     *  Add new message
     *
     * @OA\Post(
     *     path="/waitlist/messages",
     *     description="Add new message",
     *     tags={"Waitlist Messages"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "Admin",
     *              "ManagerWrite"
     *          },
     *     }},
     *
     *     @OA\Parameter(
     *         name="wait_message",
     *         in="query",
     *         description="New Message",
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
     *                 property="data",
     *                 type="object",
     *                 description="Waitinglist Message parameter list",
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="Message uuid",
     *                     example="9443407b-7eb8-4f21-8a5c-9614b4ec1bf9",
     *                 ),
     *                 @OA\Property(
     *                     property="Wait message",
     *                     type="string",
     *                     description="New Message",
     *                     example="This is just a message to subscribers on waiting list",
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
     *                  property="wait_message",
     *                  type="string",
     *                  description="message not found"
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
            $wait_message = null;
            DB::transaction(function () use ($request, &$wait_message) {
                $validator = Validator::make($request->all(), [
                    'wait_message' => 'required|string|max:1000'
                ]);

                if ($validator->fails()) {
                    return response()->jsonApi([
                        'type' => 'danger',
                        'title' => "Invalid data",
                        'message' => $validator->errors(),
                        'data' => null,
                    ], 404);
                }

                // Retrieve the validated input...
                $validated = $validator->validated();

                if ($wait_message = WaitingListMS::where('message', $validated['wait_message'])->first()) {
                    return response()->jsonApi([
                        'type' => 'danger',
                        'title' => "Adding new message failed",
                        'message' => "This same message content already exists",
                        'data' => null,
                    ], 404);
                }

                $wait_message = WaitingListMS::create($validated);
            });

            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Operation was a success',
                'message' => 'Message was added successfully',
                'data' => $wait_message,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Not operation",
                'message' => "Message was not added. Please try again.",
                'data' => null,
            ], 404);
        } catch (Throwable $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Operation failed",
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
    }

    /**
     *  Update message record
     *
     * @OA\Put(
     *     path="/waitlist/messages/{id}",
     *     description="Update message",
     *     tags={"Waitlist Messages"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "Admin",
     *              "ManagerWrite"
     *          },
     *     }},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Message id",
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
     *                 property="data",
     *                 type="object",
     *                 description="Waitlist Messages list",
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="Message uuid",
     *                     example="9443407b-7eb8-4f21-8a5c-9614b4ec1bf9",
     *                 ),
     *                 @OA\Property(
     *                     property="wait_message",
     *                     type="string",
     *                     description="Updated Message",
     *                     example="This is just a message to subscribers on waiting list",
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
     *                  property="wait_message",
     *                  type="string",
     *                  description="Name not found"
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
            $wait_message = WaitingListMS::find($id);
            $data = DB::transaction(function () use ($request, $id, &$wait_message) {
                $validator = Validator::make($request->all(), [
                    'wait_list' => 'required|string',
                ]);

                if ($validator->fails()) {
                    return [
                        'type' => 'danger',
                        'title' => "Not operation",
                        'message' => $validator->errors(),
                        'data' => null,
                    ];
                }

                // Retrieve the validated input...
                $validated = $validator->validated();

                $wait_message->update($validated);
            });
            if ($data['type'] == 'success') {
                return response()->jsonApi([
                    'type' => 'success',
                    'title' => 'Update was a success',
                    'message' => 'Message was updated successfully',
                    'data' => $data['data'],
                ], 200);
            } else {
                return response()->jsonApi($data, 404);
            }
        } catch (ModelNotFoundException $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Update failed",
                'message' => "Message does not exist",
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
     * Send message to subscribers
     *
     * @OA\POST(
     *     path="/publish/wait-messages",
     *     description="Send a new message",
     *     tags={"Waitlist Messages"},
     *
     *     security={{
     *         "default" :{
     *             "ManagerRead",
     *             "Subscriber",
     *             "ManagerWrite"
     *         },
     *     }},
     *
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Message title",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="message_id",
     *         in="query",
     *         description="New waiting list Message",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="product_url",
     *         in="query",
     *         description="Product URL",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="subscriber_ids[]",
     *         in="query",
     *         description="list of selected subscribers",
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
     *                 property="data",
     *                 type="object",
     *                 description="Waitinglist Message parameter list",
     *                 @OA\Property(
     *                     property="Title",
     *                     type="string",
     *                     description="Message title",
     *                     example="New waiting list message",
     *                 ),
     *                 @OA\Property(
     *                     property="Message",
     *                     type="string",
     *                    description="Publish new message",
     *                     example="This is just a message to subscribers on waiting list (take message id: 9681089e-ebe4-4944-95f7-f3335d4f7305)",
     *                 ),
     *                 @OA\Property(
     *                     property="Product url",
     *                     type="string",
     *                    description="Product URL",
     *                     example="https://discord.gg/DUMwfyckKy",
     *                 ),
     *                 @OA\Property(
     *                     property="Subscriber ids",
     *                     type="string",
     *                     description="Selected Subscriber Ids ",
     *                     example="subscriber 1, subscriber 2, subscriber 3",
     *                 )
     *             )
     *         )
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
     *         response="404",
     *         description="Not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="wait_message",
     *                 type="string",
     *                 description="message not found"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="500",
     *         description="Unknown error"
     *     )
     * )
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function waitingListMessage(Request $request): mixed
    {
        $validation = Validator::make($request->all(), [
            'message_id' => 'string|required',
            'subscriber_ids' => 'required|array',
        ]);

        $message = WaitingListMS::find($request->message_id);
        //
        if ($validation->fails()) {
            SubMgsId::create([
                'status' => 'failed',
                'subscriber_id' => $request->subscriber_ids,
                'message_id' => $message->id,
                'message' => $validation->errors()
            ]);
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Not operation",
                'message' => $validation->errors(),
                'data' => null,
            ], 404);
        }

        try {
            $waitListMs = SubMgsId::create([
                'message_id' => $message->id,
                'subscriber_ids' => $request->subscriber_id,
                'status' => 'delivered',
            ]);

            $data = [
                "subscriber_ids" => json_encode($request->subscriber_ids),
                "message" => $message->message,
                "title" => $request->title,
            ];
            // dd($data);
            dispatch(new PubSubService($data));
            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Message prodcast',
                'message' => 'Message was sent successfully',
                // 'data' => $waitListMs,
            ], 200);
        } catch (\Exception $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Not operation",
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
    }

    /**
     *  Delete a message
     *
     * @OA\Delete(
     *     path="/waitlist/messages/{id}",
     *     description="Delete a waitlist messages",
     *     tags={"Waitlist Messages"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "Admin",
     *              "ManagerWrite"
     *          },
     *     }},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Message id",
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
     *                 property="data",
     *                 type="object",
     *                 description="Message parameter list",
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     description="Message lists",
     *                     example="This is just a message to subscribers on waiting list",
     *                 )
     *             )
     *         )
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
     *     @OA\Response(
     *          response="404",
     *          description="Not found",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="id",
     *                  type="string",
     *                  description="Message not found"
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
            $wait_message = null;
            DB::transaction(function () use ($id, &$wait_message) {
                $wait_message = WaitingListMS::find($id);

                $wait_message->delete();

                WaitingListMS::paginate(config('settings.pagination_limit'));
            });
        } catch (ModelNotFoundException $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Delete failed",
                'message' => "Message does not exist",
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
            'message' => 'Message was deleted successfully',
            'data' => $wait_message,
        ], 204);
    }
}
