<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;

class AppController extends Controller
{
    use HttpResponses;

    /**
     * @OA\Get(
     *     path="/api/app/load-data",
     *     tags={"Data"},
     *     summary="App Load Data Rest API",
     *     description="App Load Data",
     *     operationId="load-data",
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(
     *         name="locale",
     *         in="query",
     *         description="Language to use in API",
     *         required=false,
     *         example="de",
     *         @OA\Schema(
     *             type="string",
     *          )
     *     ),
     *
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
    public function index(Request $request)
    {

        return $this->success($request->configuration, 'Application Configuration Data');
    }
}
