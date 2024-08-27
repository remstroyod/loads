<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderEvent;
use App\Http\Controllers\Controller;
use App\Http\Handlers\Api\Order\StoreFilesOrderHandler;
use App\Http\Handlers\Api\Order\StoreOrderHandler;
use App\Http\Resources\Api\OrderCollectionResource;
use App\Http\Resources\Api\OrderFilesResource;
use App\Http\Resources\Api\OrderResource;
use App\Models\Order;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{

    use HttpResponses;

    /**
     * @OA\Get(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Get Orders Rest API",
     *     description="Get Orders",
     *     operationId="orders.index",
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

    public function index(Request $request)
    {

        $limit = $request->has('limit') ? $request->get('limit') : 10;

        $orders = $request->user()->orders()->filter($request)->paginate($limit)->withQueryString();

        return $this->success(OrderCollectionResource::make($orders), 'Success!');

    }

    /**
     * @OA\Get(
     *     path="/api/orders/{order}",
     *     tags={"Orders"},
     *     summary="Show Order Rest API",
     *     description="Show Order",
     *     operationId="orders.show",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *            name="order",
     *            in="path",
     *            description="ID Order",
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
    public function show(Request $request, Order $order)
    {
        return $this->success(OrderResource::make($order), 'Success!');
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Create a New Order Rest API",
     *     description="Create a New Order",
     *     operationId="orders.store",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         description="Create user object",
     *         required=true,
     *       @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                      property="uuid",
     *                      description="Uuid",
     *                      type="string",
     *                      example="4ffe6eda-8178-fcdc-9fe2-6668b76d6203"
     *                ),
     *              @OA\Property(
     *                       property="driver_id",
     *                       description="ID Driver",
     *                       type="integer",
     *                       example=1
     *                 ),
     *              @OA\Property(
     *                        property="assign_number_car",
     *                        description="Assign Number Car",
     *                        type="string",
     *                        example="DL6665PP"
     *                  ),
     *              @OA\Property(
     *                         property="assign_number_track",
     *                         description="Assign Number Track",
     *                         type="string",
     *                         example="DL6668PP"
     *                   ),
     *              @OA\Property(
     *                          property="date_loading",
     *                          description="Date Loading",
     *                          type="string",
     *                          example="2024-07-05 10:20"
     *                    ),
     *              @OA\Property(
     *                           property="date_unloading",
     *                           description="Date Unloading",
     *                           type="string",
     *                           example="2024-07-05 10:20"
     *                     ),
     *                @OA\Property(
     *                      property="vehicleProperties",
     *                      description="Vehicle Properties",
     *                      type="array",
     *                      @OA\Items(type="string"),
     *                       example={"height": "Standard", "classification": "Plane"},
     *                ),
     *                @OA\Property(
     *                       property="totalWeight",
     *                       description="Total Weight",
     *                       type="array",
     *                       @OA\Items(type="string"),
     *                        example={"value": 24005.00, "unit": "KG"},
     *                ),
     *               @OA\Property(
     *                        property="goods",
     *                        description="Goods",
     *                        type="array",
     *                        @OA\Items(
     *                              type="object",
     *                              @OA\Property(property="description", type="string", example="Aluminium Un-Alloyed Wire Rod 1370"),
     *                              @OA\Property(property="weight", type="object",
     *                                  @OA\Property(property="value", type="number", example=24005.00),
     *                                  @OA\Property(property="unit", type="string", example="KG")
     *                              ),
     *                              @OA\Property(property="quantity", type="object",
     *                                  @OA\Property(property="value", type="number", example=11.00),
     *                                  @OA\Property(property="unit", type="string", example="KLL")
     *                              ),
     *                              @OA\Property(property="goodsTypeCode", type="string", example="METALLE"),
     *                          )
     *               ),
     *                    @OA\Property(
     *                         property="milestones",
     *                         description="Milestones",
     *                         type="array",
     *                         @OA\Items(
     *                               type="object",
     *                               @OA\Property(property="milestoneId", type="string", example="52837842-d9e2-f38c-2d8d-6c185919699f"),
     *                               @OA\Property(property="type", type="string", example="ONLOADING"),
     *                               @OA\Property(property="rta", type="object",
     *                                   @OA\Property(property="start", type="string", example="2024-07-05T07:00:00+02:00"),
     *                                   @OA\Property(property="end", type="string", example="2024-07-05T14:30:00+02:00")
     *                               ),
     *                               @OA\Property(property="address", type="object",
     *                                   @OA\Property(property="countryCode", type="string", example="NL"),
     *                                   @OA\Property(property="zipCode", type="string", example="3089"),
     *                                   @OA\Property(property="city", type="string", example="Rotterdam"),
     *                                   @OA\Property(property="loadingTimes", type="object",
     *                                      @OA\Property(property="onloading", type="object",
     *                                        @OA\Property(property="monday", type="array",
     *                                              @OA\Items(
     *                                                  @OA\Property(property="from", type="string", example="07:00"),
     *                                                  @OA\Property(property="to", type="string", example="14:30"),
     *                                              ),
     *                                        ),
     *                                        @OA\Property(property="tuesday", type="array",
     *                                               @OA\Items(
     *                                                   @OA\Property(property="from", type="string", example="07:00"),
     *                                                   @OA\Property(property="to", type="string", example="14:30"),
     *                                               ),
     *                                         ),
     *                                        @OA\Property(property="wednesday", type="array",
     *                                                @OA\Items(
     *                                                    @OA\Property(property="from", type="string", example="07:00"),
     *                                                    @OA\Property(property="to", type="string", example="14:30"),
     *                                                ),
     *                                         ),
     *                                        @OA\Property(property="thursday", type="array",
     *                                                 @OA\Items(
     *                                                     @OA\Property(property="from", type="string", example="07:00"),
     *                                                     @OA\Property(property="to", type="string", example="14:30"),
     *                                                 ),
     *                                         ),
     *                                        @OA\Property(property="friday", type="array",
     *                                                  @OA\Items(
     *                                                      @OA\Property(property="from", type="string", example="07:00"),
     *                                                      @OA\Property(property="to", type="string", example="14:30"),
     *                                                  ),
     *                                          ),
     *                                        @OA\Property(property="saturday", type="array",
     *                                                   @OA\Items(type="string"), example={}
     *                                         ),
     *                                        @OA\Property(property="sunday", type="array",
     *                                                    @OA\Items(type="string"), example={}
     *                                          ),
     *                                      ),
     *                                      @OA\Property(property="offloading", type="object",
     *                                         @OA\Property(property="monday", type="array",
     *                                               @OA\Items(
     *                                                   @OA\Property(property="from", type="string", example="07:00"),
     *                                                   @OA\Property(property="to", type="string", example="14:30"),
     *                                               ),
     *                                         ),
     *                                         @OA\Property(property="tuesday", type="array",
     *                                                @OA\Items(
     *                                                    @OA\Property(property="from", type="string", example="07:00"),
     *                                                    @OA\Property(property="to", type="string", example="14:30"),
     *                                                ),
     *                                          ),
     *                                         @OA\Property(property="wednesday", type="array",
     *                                                 @OA\Items(
     *                                                     @OA\Property(property="from", type="string", example="07:00"),
     *                                                     @OA\Property(property="to", type="string", example="14:30"),
     *                                                 ),
     *                                          ),
     *                                         @OA\Property(property="thursday", type="array",
     *                                                  @OA\Items(
     *                                                      @OA\Property(property="from", type="string", example="07:00"),
     *                                                      @OA\Property(property="to", type="string", example="14:30"),
     *                                                  ),
     *                                          ),
     *                                         @OA\Property(property="friday", type="array",
     *                                                   @OA\Items(
     *                                                       @OA\Property(property="from", type="string", example="07:00"),
     *                                                       @OA\Property(property="to", type="string", example="14:30"),
     *                                                   ),
     *                                           ),
     *                                         @OA\Property(property="saturday", type="array",
     *                                                    @OA\Items(type="string"), example={}
     *                                          ),
     *                                         @OA\Property(property="sunday", type="array",
     *                                                     @OA\Items(type="string"), example={}
     *                                           ),
     *                                       ),
     *                                   ),
     *
     *                               ),
     *                               @OA\Property(property="goodsTypeCode", type="string", example="METALLE"),
     *                           )
     *                ),
     *              @OA\Property(
     *                     property="offerPrice",
     *                     description="Offer Price",
     *                     type="integer",
     *                     example=400
     *               ),
     *             @OA\Property(
     *                      property="expiredDocuments",
     *                      description="Expired Documents",
     *                      type="array",
     *                      @OA\Items(type="string"),
     *                        example={},
     *                ),
     *              @OA\Property(
     *                       property="specialEquipment",
     *                       description="Special Equipment",
     *                       type="array",
     *                       @OA\Items(
     *                             @OA\Property(property="identifier", type="string", example="LOADING_FROM_SIDE"),
     *                       ),
     *                ),
     *               @OA\Property(
     *                       property="specialRequirements",
     *                       description="Special Requirements",
     *                       type="array",
     *                       @OA\Items(type="string"),
     *                         example={},
     *               ),
     *              @OA\Property(
     *                          property="points",
     *                          description="Points",
     *                          type="object",
     *                          @OA\Property(property="distances", type="object",
     *                                    @OA\Property(property="totalRoadKm", type="integer", example=329),
     *                                    @OA\Property(property="totalFerryKm", type="integer", example=0),
     *                                    @OA\Property(property="totalTrainKm", type="integer", example=0),
     *                                    @OA\Property(property="totalSumKm", type="integer", example=329)
     *                          ),
     *                          @OA\Property(property="routePoints", type="array",
     *                                     @OA\Items(
     *                                          type="object",
     *                                          @OA\Property(property="type", type="string", example="LOADING_POINT"),
     *                                          @OA\Property(property="coordinates", type="object",
     *                                              @OA\Property(property="latitude", type="integer", example=51.882638959),
     *                                              @OA\Property(property="longitude", type="integer", example=4.4193156234)
     *                                          ),
     *                                          @OA\Property(property="intermediatePoints", type="array",
     *                                              @OA\Items(
     *                                                  type="object",
     *                                                  @OA\Property(property="latitude", type="integer", example=51.882627655),
     *                                                  @OA\Property(property="longitude", type="integer", example=4.419309314),
     *                                              ),
     *                                          ),
     *                                         @OA\Property(property="loadingPointNumber", type="integer", example=1),
     *                                         @OA\Property(property="milestoneId", type="string", example="52837842-d9e2-f38c-2d8d-6c185919699f"),
     *                                      ),
     *
     *                           ),
     *
     *              ),
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
    public function store(Request $request, StoreOrderHandler $handler, Order $order)
    {

        $order = $handler->process($request);

        if (is_object($order))
        {
            event(new OrderEvent($order));

            return $this->success(OrderResource::make($order));
        }

        return $this->error(message: $order, code: 401);

    }

    /**
     * @OA\Put(
     *     path="/api/orders/{order}",
     *     tags={"Orders"},
     *     summary="Update Order Rest API",
     *     description="Update Order",
     *     operationId="orders.update",
     *     security={{"bearer":{}}},
 *         @OA\Parameter(
     *             name="order",
     *             in="path",
     *             description="ID Order",
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
     *                      description="Order Status",
     *                      type="integer",
     *                      example=1
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
    public function update(Request $request, StoreOrderHandler $handler, Order $order)
    {

        $order = $handler->process($request, $order);

        if (is_object($order))
        {
            return $this->success(OrderResource::make($order));
        }

        return $this->error(message: $order, code: 401);

    }

    /**
     * @OA\Post(
     *     path="/api/orders/{order}/upload",
     *     tags={"Orders"},
     *     summary="Upload File Rest API",
     *     description="Upload File Driver",
     *     operationId="orders.upload",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *             name="order",
     *             in="path",
     *             description="ID Order",
     *             required=true,
     *             example=1
     *      ),
     *     @OA\RequestBody(
     *         description="Create user object",
     *         required=true,
     *       @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                      property="file",
     *                      description="Upload File",
     *                      type="string",
     *                      format="binary",
     *                 ),
     *            )
     *        )
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
    public function uploadDocument(Request $request, StoreFilesOrderHandler $handler, Order $order)
    {
        $request->merge(['type' => 1]);

        $file = $handler->process($request, $order);

        if (is_object($file))
        {
            return $this->success(OrderFilesResource::make($file));
        }

        return $this->error(message: $file, code: 401);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{order}/documents",
     *     tags={"Orders"},
     *     summary="Get Documents Rest API",
     *     description="Get Documents",
     *     operationId="orders.documents",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *              name="order",
     *              in="path",
     *              description="ID Order",
     *              required=true,
     *              example=1
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
    public function documents(Request $request, Order $order)
    {

        $documentsUser = $order->userDocuments;
        $documentsAdmin = $order->adminDocuments;

        return $this->success([
            'user_documents' => OrderFilesResource::collection($documentsUser),
            'admin_documents' => OrderFilesResource::collection($documentsAdmin),
        ], 'Success!');

    }

    /**
     * @OA\Delete(
     *     path="/api/orders/{order}/documents/{document}",
     *     tags={"Orders"},
     *     summary="Delete Document Rest API",
     *     description="Delete Document",
     *     operationId="orders.documents.destroy",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *              name="order",
     *              in="path",
     *              description="ID Order",
     *              required=true,
     *              example=1
     *     ),
     *     @OA\Parameter(
     *               name="document",
     *               in="path",
     *               description="ID Document",
     *               required=true,
     *               example=1
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
    public function destroyDocument(Request $request, Order $order, $document)
    {

        if($order->userDocuments()->findOrFail($document)->delete())
        {
            return $this->success(message: 'Success!');
        }

        return $this->error('', "Error Delete", 401);

    }

    /**
     * @OA\Get(
     *     path="/api/orders/{order}/documents/download/{document}",
     *     tags={"Orders"},
     *     summary="Download Document Rest API",
     *     description="Download Document",
     *     operationId="orders.documents.download",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *              name="order",
     *              in="path",
     *              description="ID Order",
     *              required=true,
     *              example=1
     *     ),
     *     @OA\Parameter(
     *               name="document",
     *               in="path",
     *               description="ID Document",
     *               required=true,
     *               example=1
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
    public function downloadDocument(Request $request, Order $order, $document)
    {

        $doc = $order->userDocuments()->findOrFail($document);
        $fileName = $doc->name;

        $fileContent = Storage::disk('google')->get($fileName);

        $mimeType = Storage::disk('google')->mimeType($fileName);

        return response($fileContent, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');

    }

}
