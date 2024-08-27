<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ResultController extends Controller
{

    use HttpResponses;

    /**
     * @OA\Get(
     *     path="/api/results",
     *     tags={"Results"},
     *     summary="Get Results Rest API",
     *     description="Get Results",
     *     operationId="results.index",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *          description="Create user object",
     *          required=false,
     *          @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                    property="dateStart",
     *                    description="Date Start",
     *                    type="string",
     *                    example="2024-05-26"
     *                 ),
     *                @OA\Property(
     *                     property="dateEnd",
     *                     description="Date End",
     *                     type="string",
     *                     example="2024-05-26"
     *                  ),
     *                @OA\Property(
     *                     property="countryStart",
     *                     description="Country Start",
     *                     type="string",
     *                     example="GR"
     *                  ),
     *                 @OA\Property(
     *                      property="countryEnd",
     *                      description="Country End",
     *                      type="string",
     *                      example="BG"
     *                   ),
     *              ),
     *          ),
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

    public function index(Request $request)
    {
        if (empty(env('PARSER_GET_RESULTS_URL'))) {
            return $this->error(message: 'Error: URL not empty');
        }

        $limit = $request->has('limit') ? $request->get('limit') : 20;
        $page = $request->has('page') ? $request->get('page') : 1;

        $dateStart = Carbon::createFromFormat('Y-m-d', $request->get('dateStart'), '+03:00');
        $dateStart->setTime(Carbon::now()->hour, Carbon::now()->minute, Carbon::now()->second);
        $startDate = $dateStart->toIso8601String();

        $dateEnd = Carbon::createFromFormat('Y-m-d', $request->get('dateEnd'), '+03:00');
        $dateEnd->endOfDay();
        $endDate = $dateEnd->toIso8601String();

        $user = $request->user();
        $user = $user->id;
        $dS = $request->get('dateStart');
        $dE = $request->get('dateEnd');
        $countryStart = $request->get('countryStart');
        $countryEnd = $request->get('countryEnd');

        $cacheKey = "result_${user}_${dS}_${dE}_${countryStart}_${countryEnd}";

        if (Cache::has($cacheKey) && $limit > 20) {

            $arr = Cache::get($cacheKey);

        } else {

            $body = [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'countryStart' => !empty($countryStart) ? $countryStart : '',
                'countryEnd' => !empty($countryEnd) ? $countryEnd : ''
            ];

            $response = Http::timeout(100)->post(env('PARSER_GET_RESULTS_URL'), $body);

            if (!$response->collect()->has('result'))
            {
                return $this->error(message: 'Could not get parse result: ' . $response->body());
            }

            $arr = $response->collect()->get('result');
            Cache::put($cacheKey, $arr, 200); // кешируем на 200 сек
        }

        $paginator = $this->paginateData($arr, $limit, $page);

        $response = [
            'status' => true,
            'message' => 'Success!',
            'data' => [
                'data' => $paginator->items(),
                'pagination' => [
                    'total' => $paginator->total(),
                    'perPage' => $paginator->perPage(),
                    'currentPage' => $paginator->currentPage(),
                    'lastPage' => $paginator->lastPage(),
                    'nextPageUrl' => $paginator->nextPageUrl(),
                    'previousPageUrl' => $paginator->previousPageUrl(),
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                ],
            ],
        ];

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/results/{id}",
     *     tags={"Results"},
     *     summary="Get Result Offer Rest API",
     *     description="Get Result Offer",
     *     operationId="results.index.show",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *           name="id",
     *           in="path",
     *           description="ID Document",
     *           required=true,
     *           example="b5380f06-7a54-f78d-aa4e-8a3b79b1c3c6"
     *
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
    public function show(string $id)
    {
        $url = sprintf('%s/%s', env('PARSER_GET_RESULTS_OFFER_URL'), $id);
        $response = Http::timeout(100)->get($url);

        if($response->ok() && $response->collect()->count())
        {
            return $this->success($response->collect());
        }

        return $this->error($response->body(), "Error", 404);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function paginateData($items, int $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
        $items = array_values($items);
        $paginator = new LengthAwarePaginator(
            array_slice($items, ($page - 1) * $perPage, $perPage),
            count($items),
            $perPage,
            $page,
            $options
        );
        return $paginator;
    }
}
