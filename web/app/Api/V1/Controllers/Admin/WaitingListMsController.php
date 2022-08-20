<?php

namespace App\Api\V1\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Listeners\WaitingListMSListener;
use App\Models\SubMgsId;
use App\Models\Subscriber;
use App\Models\WaitingListMS;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class WaitingListMsController extends Controller
{
    /**
     *  Display a listing of messages
     *
     * @OA\Get(
     *     path="/admin/messages",
     *     description="All messages",
     *     tags={"Admin | Waitlist Messages"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "Admin",
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
     *                 property="data",
     *                 type="object",
     *                 description="Messages parameter list",
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     description="Messages",
     *                     example="All new messages",
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
     *                  property="messages",
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
    public function index()
    {
        $waitingListMs = WaitingListMS::with('submgId.subscribe')->all();
        return response()->jsonApi([
            'title' => 'Operation was success',
            'message' => 'The data was displayed successfully',
            'data' => $waitingListMs
        ]);
    }

    /**
     *  Add new message
     *
     * @OA\Post(
     *     path="/admin/waitlist/messages",
     *     description="Add new message",
     *     tags={"Admin | Waitlist Messages"},
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
        $validator = Validator::make($request->all(), [
            'platform' => 'required',
            'id' => 'required',
            'message' => 'required',
        ]);
        $validated = $validator->validated();
        $message = WaitingListMS::create($validated);

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
                    dispatch(new WaitingListMSListener($data));
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
                dispatch(new WaitingListMSListener($data));
            }
        }
    }

    /**
     *  Update message record
     *
     * @OA\Put(
     *     path="/admin/waitlist/messages/{id}",
     *     description="Update message",
     *     tags={"Admin | Waitlist Messages"},
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
     *
     *     @OA\Response(
     *         response="200",
     *         description="Output data",
     *
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="Admin | Waitlist Messages list",
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
                        'title' => "Not operation",
                        'message' => $validator->errors(),
                    ];
                }

                // Retrieve the validated input...
                $validated = $validator->validated();

                $wait_message->update($validated);
            });
            if ($data['type'] == 'success') {
                return response()->jsonApi([
                    'title' => 'Update was a success',
                    'message' => 'Message was updated successfully',
                    'data' => $data['data'],
                ]);
            } else {
                return response()->jsonApi($data, 404);
            }
        } catch (ModelNotFoundException $e) {
            return response()->jsonApi([
                'title' => "Update failed",
                'message' => "Message does not exist",
            ], 404);
        } catch (Throwable $e) {
            return response()->jsonApi([
                'title' => "Update failed",
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     *  Send message to subscribers
     *
     * @OA\POST(
     *     path="/admin/publish/wait-messages",
     *     description="Send a new message",
     *     tags={"Admin | Waitlist Messages"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "Subscriber",
     *              "ManagerWrite"
     *          },
     *     }},
     *
     *   @OA\Parameter(
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
                'title' => "Not operation",
                'message' => $validation->errors(),
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
                "message_id" => $message->id,
                "title" => $request->title,
                "product_url" => $request->url,
            ];
            // dd($data);
            dispatch(new WaitingListMSListener($data));
            return response()->jsonApi([
                'title' => 'Message prodcast',
                'message' => 'Message was sent successfully',
                // 'data' => $waitListMs,
            ]);
        } catch (Exception $e) {
            return response()->jsonApi([
                'title' => "Not operation",
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     *  Delete a message
     *
     * @OA\Delete(
     *     path="/admin/delete/messages/{id}",
     *     description="Delete a Admin | waitlist messages",
     *     tags={"Admin | Waitlist Messages"},
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
                'title' => "Delete failed",
                'message' => "Message does not exist",
            ], 404);
        } catch (Throwable $e) {
            return response()->jsonApi([
                'title' => "Delete failed",
                'message' => $e->getMessage(),
            ], 404);
        }
        return response()->jsonApi([
            'title' => 'Operation was a success',
            'message' => 'Message was deleted successfully',
            'data' => $wait_message,
        ], 204);
    }
}
