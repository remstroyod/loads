<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class UserController extends Controller
{

    use HttpResponses;

    /**
     * @OA\Get(
     *     path="/api/user/show",
     *     tags={"User"},
     *     summary="User Show Rest API",
     *     description="User Show",
     *     operationId="user.show",
     *     security={{"bearer":{}}},
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
    public function show(Request $request)
    {
        return $this->success(UserResource::make($request->user()));
    }

}
