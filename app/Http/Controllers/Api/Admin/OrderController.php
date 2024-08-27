<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Handlers\Api\Order\StoreFilesOrderHandler;
use App\Http\Handlers\Api\Order\StoreOrderHandler;
use App\Http\Resources\Api\Admin\OrderCollectionResource;
use App\Http\Resources\Api\Admin\OrderResource;
use App\Http\Resources\Api\OrderFilesResource;
use App\Models\Order;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{

    use HttpResponses;

    /**
     * @OA\Get(
     *     path="/api/admin/orders",
     *     tags={"Admin: Orders"},
     *     summary="Get All Orders Rest API",
     *     description="Get All Orders",
     *     operationId="admin.orders.index",
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
     *      @OA\Parameter(
     *            name="order_id",
     *            in="query",
     *            description="Order Number of returned orders",
     *            required=false,
     *            @OA\Schema(
     *                type="string",
     *        ),
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
     *     @OA\Parameter(
     *             name="date_from",
     *             in="query",
     *             description="Date From of returned orders",
     *             required=false,
     *             @OA\Schema(
     *                 type="string",
     *                 example="2024-07-08",
     *         ),
     *      ),
     *     @OA\Parameter(
     *              name="date_to",
     *              in="query",
     *              description="Date To of returned orders",
     *              required=false,
     *              @OA\Schema(
     *                  type="string",
     *                  example="2024-07-08",
     *          ),
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

    public function index(Request $request, Order $order)
    {

        $limit = $request->has('limit') ? $request->get('limit') : 10;

        $orders = $order->whereIsManager($request)->filter($request)->paginate($limit)->withQueryString();

        return $this->success(OrderCollectionResource::make($orders));

    }

    /**
     * @OA\Put(
     *     path="/api/admin/orders/{order}",
     *     tags={"Admin: Orders"},
     *     summary="Update Order Rest API",
     *     description="Update Order",
     *     operationId="admin.orders.update",
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
     *               @OA\Property(
     *                       property="order_id",
     *                       description="Order ID",
     *                       type="string",
     *                       example="HT-456-YTR"
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
     *     path="/api/admin/orders/{order}/upload",
     *     tags={"Admin: Orders"},
     *     summary="Upload File Rest API",
     *     description="Upload File Driver",
     *     operationId="admin.orders.upload",
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
        $request->merge(['type' => 2]);

        $file = $handler->process($request, $order);

        if (is_object($file))
        {
            return $this->success(OrderFilesResource::make($file));
        }

        return $this->error(message: $file, code: 401);

    }

    /**
     * @OA\Get(
     *     path="/api/admin/orders/{order}/documents",
     *     tags={"Admin: Orders"},
     *     summary="Get Documents Rest API",
     *     description="Get Documents",
     *     operationId="admin.orders.documents",
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
     *     path="/api/admin/orders/{order}/documents/{document}",
     *     tags={"Admin: Orders"},
     *     summary="Delete Document Rest API",
     *     description="Delete Document",
     *     operationId="admin.orders.documents.destroy",
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

        if($order->adminDocuments()->findOrFail($document)->delete())
        {
            return $this->success(message: 'Success!');
        }

        return $this->error('', "Error Delete", 401);

    }

    /**
     * @OA\Get(
     *     path="/api/admin/orders/{order}/documents/download/{document}",
     *     tags={"Admin: Orders"},
     *     summary="Download Document Rest API",
     *     description="Download Document",
     *     operationId="admin.orders.documents.download",
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

        $doc = $order->adminDocuments()->findOrFail($document);
        $fileName = $doc->name;

        $fileContent = Storage::disk('google')->get($fileName);

        $mimeType = Storage::disk('google')->mimeType($fileName);

        return response($fileContent, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');

    }

    /**
     * @OA\Get(
     *     path="/api/admin/orders/{order}",
     *     tags={"Admin: Orders"},
     *     summary="Order Detail Rest API",
     *     description="Order Detail",
     *     operationId="admin.orders.show",
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
    public function show(Request $request, Order $order)
    {
        return $this->success(OrderResource::make($order));
    }

}
