<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Handlers\Api\User\StoreManagerUserHandler;
use App\Http\Handlers\Api\User\UpdateUserHandler;
use App\Http\Resources\Api\Admin\UserCollectionResource;
use App\Http\Resources\Api\OrderCollectionResource;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class UserController extends Controller
{

    use HttpResponses;

    /**
     * @OA\Get(
     *     path="/api/admin/users",
     *     tags={"Admin: Users"},
     *     summary="Get All Users Rest API",
     *     description="Get All Users",
     *     operationId="admin.users.index",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="Limit the number of returned orders",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *      ),
     *      @OA\Parameter(
     *           name="page",
     *           in="query",
     *           description="Page the number of returned orders",
     *           required=false,
     *           @OA\Schema(
     *               type="integer",
     *       ),
     *     ),
     *     @OA\Parameter(
     *            name="status",
     *            in="query",
     *            description="Status of returned orders",
     *            required=false,
     *            @OA\Schema(
     *                type="integer",
     *        ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */

    public function index(Request $request, User $user)
    {

        $limit = $request->has('limit') ? $request->get('limit') : 10;

        $user = $user->withoutGlobalScopes()
            ->withoutCurrentUser()
            ->onlyUsers()
            ->isShowManager()
            ->filter($request)
            ->orderBy('id', 'desc')
            ->paginate($limit)
            ->withQueryString();

        return $this->success(UserCollectionResource::make($user));

    }

    /**
     * @OA\Put(
     *     path="/api/admin/users/{user}",
     *     tags={"Admin: Users"},
     *     summary="Update User Rest API",
     *     description="Update User",
     *     operationId="admin.users.update",
     *     security={{"bearer":{}}},
     *         @OA\Parameter(
     *             name="user",
     *             in="path",
     *             description="ID User",
     *             required=true,
     *             example=1
     *       ),
     *     @OA\RequestBody(
     *         description="Create user object",
     *         required=true,
     *       @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                      property="status",
     *                      description="User Status",
     *                      type="integer",
     *                      example=1
     *                ),
     *               @OA\Property(
     *                       property="role",
     *                       description="User Role",
     *                       type="integer",
     *                       example=2
     *               ),
     *              @OA\Property(
     *                       property="users",
     *                       description="Users Attach Manager",
     *                       type="array",
     *                       @OA\Items(
     *                           type="integer"
     *                       ),
     *                       example={1,2,3}
     *                 ),
     *            ),
     *        ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function update(Request $request, UpdateUserHandler $handler, $user)
    {

        $user = $handler->process($request, $user);

        if (is_object($user))
        {
            return $this->success(UserResource::make($user));
        }

        return $this->error(message: $user, code: 401);

    }

    /**
     * @OA\Delete(
     *     path="/api/admin/users/{user}",
     *     tags={"Admin: Users"},
     *     summary="Delete User Rest API",
     *     description="Delete User",
     *     operationId="admin.users.destroy",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *            name="user",
     *            in="path",
     *            description="ID User",
     *            required=true,
     *            example=1
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function destroy(Request $request, $user)
    {

        $user = User::withoutGlobalScopes()->find($user);

        if(!$user)
        {
            return $this->error('', "User Not Found", 401);
        }

        if($user->delete())
        {
            return $this->success(message: 'Success!');
        }

        return $this->error('', "Error Delete", 401);

    }

    /**
     * @OA\Get(
     *     path="/api/admin/users/managers/all",
     *     tags={"Admin: Users"},
     *     summary="Get All Managers Rest API",
     *     description="Get All Managers",
     *     operationId="admin.users.managers.index",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="Limit the number of returned orders",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *      ),
     *      @OA\Parameter(
     *           name="page",
     *           in="query",
     *           description="Page the number of returned orders",
     *           required=false,
     *           @OA\Schema(
     *               type="integer",
     *       ),
     *     ),
     *     @OA\Parameter(
     *            name="status",
     *            in="query",
     *            description="Status of returned orders",
     *            required=false,
     *            @OA\Schema(
     *                type="integer",
     *        ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function managers(Request $request, User $user)
    {

        $limit = $request->has('limit') ? $request->get('limit') : 10;

        $user = $user->withoutGlobalScopes()
            ->withoutCurrentUser()
            ->onlyManagers()
            ->filter($request)
            ->orderBy('id', 'desc')
            ->paginate($limit)
            ->withQueryString();

        return $this->success(UserCollectionResource::make($user));

    }

    /**
     * @OA\Post(
     *     path="/api/admin/users",
     *     tags={"Admin: Users"},
     *     summary="Store User Rest API",
     *     description="Store User",
     *     operationId="admin.users.store",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         description="Create user object",
     *         required=true,
     *       @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                       property="name",
     *                       description="User Name",
     *                       type="string",
     *                       example="Manager Name"
     *                 ),
     *                @OA\Property(
     *                        property="surname",
     *                        description="User SurName",
     *                        type="string",
     *                        example="Manager Surname"
     *                 ),
     *                @OA\Property(
     *                         property="email",
     *                         description="User Email",
     *                         type="string",
     *                         example="manager@example.com"
     *                 ),
     *                @OA\Property(
     *                          property="password",
     *                          description="User Password",
     *                          type="string",
     *                          example="123456"
     *                 ),
     *                @OA\Property(
     *                           property="gender",
     *                           description="User Gender",
     *                           type="integer",
     *                           example=2
     *                 ),
     *                @OA\Property(
     *                            property="status",
     *                            description="User Status",
     *                            type="integer",
     *                            example=1
     *                 ),
     *                @OA\Property(
     *                            property="role",
     *                            description="User Role",
     *                            type="integer",
     *                            example=2
     *                 ),
     *                @OA\Property(
     *                      property="users",
     *                      description="Users Attach Manager",
     *                      type="array",
     *                      @OA\Items(
     *                          type="integer"
     *                      ),
     *                      example={1,2,3}
     *                ),
     *            ),
     *        ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function store(Request $request, StoreManagerUserHandler $handler, User $user)
    {

        $user = $handler->process($request);

        if (is_object($user))
        {
            return $this->success(UserResource::make($user));
        }

        return $this->error(message: $user, code: 401);

    }

    /**
     * @OA\Post(
     *     path="/api/admin/users/managers/reattach/{userFrom}/{userTo}",
     *     tags={"Admin: Users"},
     *     summary="Reattach Users Manager to Manager Rest API",
     *     description="Reattach User Manager to Manager",
     *     operationId="admin.users.managers.reattach",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *             name="userFrom",
     *             in="path",
     *             description="ID Manager From",
     *             required=true,
     *             example=1
     *      ),
     *     @OA\Parameter(
     *              name="userTo",
     *              in="path",
     *              description="ID Manager To",
     *              required=true,
     *              example=1
     *       ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function reattach(Request $request, User $userFrom, User $userTo)
    {

        $userFrom->onlyUserManager()->update([
            'manager_id' => $userTo->id
        ]);

        return $this->success();

    }

    /**
     * @OA\Post(
     *     path="/api/admin/users/managers/attach/{manager}/{user}",
     *     tags={"Admin: Users"},
     *     summary="Attach User to Manager Rest API",
     *     description="Attach User to Manager",
     *     operationId="admin.users.managers.attach",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *             name="manager",
     *             in="path",
     *             description="ID Manager",
     *             required=true,
     *             example=1
     *      ),
     *     @OA\Parameter(
     *              name="user",
     *              in="path",
     *              description="ID User",
     *              required=true,
     *              example=1
     *       ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function attach(Request $request, $manager, $user)
    {
        $manager = User::withoutGlobalScopes()->findOrFail($manager);
        $user = User::withoutGlobalScopes()->findOrFail($user);

        $manager->manager()->save($user);

        return $this->success(data: UserResource::make($user), message: __('Success!'));

    }

    /**
     * @OA\Get(
     *     path="/api/admin/users/{user}/orders",
     *     tags={"Admin: Users"},
     *     summary="Get All Orders by Users Rest API",
     *     description="Get All Orders by Users",
     *     operationId="admin.users.orders",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *               name="user",
     *               in="path",
     *               description="ID User",
     *               required=true,
     *               example=1
     *      ),
     *     @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="Limit the number of returned orders",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *      ),
     *      @OA\Parameter(
     *           name="page",
     *           in="query",
     *           description="Page the number of returned orders",
     *           required=false,
     *           @OA\Schema(
     *               type="integer",
     *       ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function orders(Request $request, User $user)
    {

        $limit = $request->has('limit') ? $request->get('limit') : 10;

        $orders = $user->orders()->filter($request)->paginate($limit)->withQueryString();

        return $this->success(OrderCollectionResource::make($orders));

    }

}
