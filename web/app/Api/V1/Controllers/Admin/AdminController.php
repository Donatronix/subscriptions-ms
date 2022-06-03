<?php

namespace App\Api\V1\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AdminController extends Controller
{
    /**
     *  Display a listing of the admins
     *
     * @OA\Get(
     *     path="/admin/admins",
     *     description="Get all admins",
     *     tags={"Admins"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "Admin",
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
     *         name="role",
     *         in="query",
     *         description="Admin role",
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
     *                 description="Admin parameter list",
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="Admin uuid",
     *                     example="9443407b-7eb8-4f21-8a5c-9614b4ec1bf9",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name",
     *                     example="Vasya",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Admin email",
     *                     example="admin@mail.com",
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string",
     *                     description="Admin phone number",
     *                     example="++44625546453",
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     type="string",
     *                     description="Admin role",
     *                     example="admin",
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
     *                  description="Uuid admin not found"
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="Name not found"
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  description="Email not found"
     *              ),
     *              @OA\Property(
     *                  property="phone",
     *                  type="string",
     *                  description="Phone not found"
     *              ),
     *              @OA\Property(
     *                  property="role",
     *                  type="string",
     *                  description="Role not found"
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

            $admins = Admin::where('role', $request->get('role'))->paginate($request->get('limit', config('settings.pagination_limit')));

            return response()->jsonApi(
                array_merge([
                    'type' => 'success',
                    'title' => 'Operation was success',
                    'message' => 'The data was displayed successfully',
                ], ['data' => $admins->toArray() ?? []]),
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
                'title' => "Operation failed",
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
    }

    /**
     *  Display a admin.
     *
     * @OA\Get(
     *     path="/admin/admins/{id}",
     *     description="Get admin by id",
     *     tags={"Admins"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "Admin",
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
     *                 property="data",
     *                 type="object",
     *                 description="Admin parameter list",
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="Admin uuid",
     *                     example="9443407b-7eb8-4f21-8a5c-9614b4ec1bf9",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name",
     *                     example="Vasya",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Admin email",
     *                     example="admin@mail.com",
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string",
     *                     description="Admin phone number",
     *                     example="++44625546453",
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     type="string",
     *                     description="Admin role",
     *                     example="admin",
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
     *                  description="Uuid admin not found"
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="Name not found"
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  description="Email not found"
     *              ),
     *              @OA\Property(
     *                  property="phone",
     *                  type="string",
     *                  description="Phone not found"
     *              ),
     *              @OA\Property(
     *                  property="role",
     *                  type="string",
     *                  description="Role not found"
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
            $admin = Admin::find($id);

            return response()->jsonApi(
                array_merge([
                    'type' => 'success',
                    'title' => 'Operation was success',
                    'message' => 'Admin was displayed successfully',
                ], ['data' => $admin?->toArray() ?? []]),
                200);

        } catch (ModelNotFoundException $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Not operation",
                'message' => "Admin does not exist",
                'data' => null,
            ], 404);
        } catch (Throwable $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Get admin failed",
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
    }

    /**
     *  Add new admin
     *
     * @OA\Post(
     *     path="/admin/admins",
     *     description="Add new admin",
     *     tags={"Admins"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "Admin",
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
     *         name="phone",
     *         in="query",
     *         description="Admin phone number",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Admin name",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Admin email",
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
     *                 description="Admin parameter list",
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="Admin uuid",
     *                     example="9443407b-7eb8-4f21-8a5c-9614b4ec1bf9",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name",
     *                     example="Vasya",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Admin email",
     *                     example="admin@mail.com",
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string",
     *                     description="Admin phone number",
     *                     example="+4432366456945",
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     type="string",
     *                     description="Admin role",
     *                     example="admin",
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
     *                  description="Uuid admin not found"
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="Name not found"
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  description="Email not found"
     *              ),
     *              @OA\Property(
     *                  property="phone",
     *                  type="string",
     *                  description="Phone not found"
     *              ),
     *              @OA\Property(
     *                  property="role",
     *                  type="string",
     *                  description="Role not found"
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
            $admin = null;
            DB::transaction(function () use ($request, &$admin) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|unique:admins,email',
                    'phone' => 'required|string|unique:admins,phone',
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


                if ($admin = Admin::where('email', $validated['email'])->first()) {
                    return response()->jsonApi([
                        'type' => 'danger',
                        'title' => "Adding new admin failed",
                        'message' => "Admin already exists",
                        'data' => null,
                    ], 404);
                }

                if ($admin = Admin::where('name', $validated['name'])->first()) {
                    return response()->jsonApi([
                        'type' => 'danger',
                        'title' => "Operation failed",
                        'message' => "Name already in use",
                        'data' => null,
                    ], 404);
                }

                $admin = Admin::create($validated);
            });

            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Operation was a success',
                'message' => 'Admin was added successfully',
                'data' => $admin->toArray(),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Not operation",
                'message' => "Admin was not added. Please try again.",
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
     *  Update admin record
     *
     * @OA\Put(
     *     path="/admin/admins/{id}",
     *     description="Update admin",
     *     tags={"Admins"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "Admin",
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
     *         description="Admin user id",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Admin name",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Admin email",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="Admin phone number",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Admin user id",
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
     *                 description="Admin parameter list",
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="Admin uuid",
     *                     example="9443407b-7eb8-4f21-8a5c-9614b4ec1bf9",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name",
     *                     example="Vasya",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Admin email",
     *                     example="sumra chat",
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string",
     *                     description="Admin phone number",
     *                     example="+445667474124146",
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     type="string",
     *                     description="Admin role",
     *                     example="admin",
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
     *                  description="Uuid admin not found"
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="Name not found"
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  description="Email not found"
     *              ),
     *              @OA\Property(
     *                  property="phone",
     *                  type="string",
     *                  description="Phone not found"
     *              ),
     *              @OA\Property(
     *                  property="role",
     *                  type="string",
     *                  description="Role not found"
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
            $admin = Admin::find($id);
            $data = DB::transaction(function () use ($request, $id, &$admin) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string',
                    'email' => 'required|string',
                    'phone' => 'required|string',
                ]);

                if ($validator->fails()) {
                    return [
                        'type' => 'danger',
                        'title' => "Not operation",
                        'message' => $validator->messages()->toArray(),
                        'data' => null,
                    ];
                }

                // Retrieve the validated input...
                $validated = $validator->validated();

                $admin->update($validated);
                return [
                    'type' => 'success',
                    'title' => 'Update was a success',
                    'message' => 'Admin was updated successfully',
                    'data' => $admin,
                ];
            });

            if($data['type'] == 'success'){
            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Update was a success',
                'message' => 'Admin was updated successfully',
                'data' => $data['data'],
            ], 200);
        }else{
            return response()->jsonApi($data, 404);
        }
        } catch (ModelNotFoundException $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Update failed",
                'message' => "Admin does not exist",
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
     *  Delete admin record
     *
     * @OA\Delete(
     *     path="/admin/admins/{id}",
     *     description="Delete admin",
     *     tags={"Admins"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "Admin",
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
     *         description="Admin user id",
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
     *                 description="Admin parameter list",
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="Admin uuid",
     *                     example="9443407b-7eb8-4f21-8a5c-9614b4ec1bf9",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name",
     *                     example="Vasya",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Admin email",
     *                     example="sumra@mail.com",
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string",
     *                     description="Admin phone number",
     *                     example="+44625546453",
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     type="string",
     *                     description="Admin role",
     *                     example="admin",
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
     *                  description="Uuid admin not found"
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="Name not found"
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  description="Email not found"
     *              ),
     *              @OA\Property(
     *                  property="phone",
     *                  type="string",
     *                  description="Phone not found"
     *              ),
     *              @OA\Property(
     *                  property="role",
     *                  type="string",
     *                  description="Role not found"
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
            $admins = null;
            DB::transaction(function () use ($id, &$admins) {
                $admin = Admin::find($id);

                $admin->delete();

                $admins = Admin::paginate(config('settings.pagination_limit'));
            });
        } catch (ModelNotFoundException $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Delete failed",
                'message' => "Admin does not exist",
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
            'message' => 'Admin was deleted successfully',
            'data' => $admins->toArray(),
        ], 200);
    }

    /**
     *  Update admin role
     *
     * @OA\Patch(
     *     path="/admin/admins/{id}",
     *     description="Update admin role",
     *     tags={"Admins"},
     *
     *     security={{
     *          "default" :{
     *              "ManagerRead",
     *              "Admin",
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
     *         name="role",
     *         in="query",
     *         description="Admin role",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Admin user id",
     *         @OA\Schema(
     *             type="string"
     *         ),
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
     *                 description="Admin parameter list",
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="Admin uuid",
     *                     example="9443407b-7eb8-4f21-8a5c-9614b4ec1bf9",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name",
     *                     example="Vasya",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Admin email",
     *                     example="sumra chat",
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string",
     *                     description="Admin phone number",
     *                     example="+445667474124146",
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     type="string",
     *                     description="Admin role",
     *                     example="admin",
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
     *                  description="Uuid admin not found"
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="Name not found"
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  description="Email not found"
     *              ),
     *              @OA\Property(
     *                  property="phone",
     *                  type="string",
     *                  description="Phone not found"
     *              ),
     *              @OA\Property(
     *                  property="role",
     *                  type="string",
     *                  description="Role not found"
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
    public function updateRole(Request $request, $id): mixed
    {
        try {
            $admin = Admin::find($id);
            DB::transaction(function () use ($request, $id, &$admin) {
                $validator = Validator::make($request->all(), [
                    'role' => 'required|string',
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

                $admin->update($validated);
            });
            
            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Update was a success',
                'message' => 'Admin was updated successfully',
                'data' => $admin->toArray(),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => "Update failed",
                'message' => "Admin does not exist",
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

}
