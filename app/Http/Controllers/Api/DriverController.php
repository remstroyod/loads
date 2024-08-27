<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Handlers\Api\Driver\StoreDriverHandler;
use App\Http\Resources\Api\DriverResource;
use App\Models\Driver;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class DriverController extends Controller
{

    use HttpResponses;

    /**
     * @OA\Get(
     *     path="/api/drivers",
     *     tags={"Drivers"},
     *     summary="Get Drivers Rest API",
     *     description="Get Drivers",
     *     operationId="drivers.index",
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

        $drivers = $request->user()->drivers()->get();

        return $this->success(DriverResource::collection($drivers), 'Success!');

    }

    /**
     * @OA\Get(
     *     path="/api/drivers/{driver}",
     *     tags={"Drivers"},
     *     summary="Show Driver Rest API",
     *     description="Show Driver",
     *     operationId="drivers.show",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *            name="driver",
     *            in="path",
     *            description="ID Driver",
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
    public function show(Request $request, Driver $driver)
    {
        return $this->success(DriverResource::make($driver), 'Success!');
    }

    /**
     * @OA\Post(
     *     path="/api/drivers",
     *     tags={"Drivers"},
     *     summary="Create a New Driver Rest API",
     *     description="Create a New Driver",
     *     operationId="drivers.store",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         description="Create user object",
     *         required=true,
     *       @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                    property="gender",
     *                    description="Gender",
     *                    type="integer",
     *                    example=1
     *                ),
     *                @OA\Property(
     *                    property="first_name",
     *                    description="First Name",
     *                    type="string",
     *                    example="Driver Name"
     *                ),
     *                @OA\Property(
     *                      property="surname",
     *                      description="Surname",
     *                      type="string",
     *                      example="Drive Surname"
     *                 ),
     *                @OA\Property(
     *                      property="phone",
     *                      description="Phone",
     *                      type="string",
     *                      example="+380960000000"
     *                 ),
     *                @OA\Property(
     *                      property="email",
     *                      description="Email",
     *                      type="string",
     *                      example="example@hostname.com"
     *                 ),
     *                @OA\Property(
     *                      property="has_license",
     *                      description="License",
     *                      type="integer",
     *                      example=1
     *                 ),
     *                @OA\Property(
     *                      property="languages",
     *                      description="Languages",
     *                      type="array",
     *                      @OA\Items(type="string"),
     *                       example={"RU", "BG", "PL"},
     *                 ),
     *                @OA\Property(
     *                      property="file",
     *                      description="Upload File",
     *                      type="string",
     *                      format="binary",
     *                 ),
     *                @OA\Property(
     *                      property="file_type",
     *                      description="Type File",
     *                      type="integer",
     *                      example=1
     *                  ),
     *                @OA\Property(
     *                      property="file_number",
     *                      description="Number File",
     *                      type="string",
     *                      example="12-RT-54"
     *                 ),
     *                @OA\Property(
     *                      property="file_valid_from",
     *                      description="Date Valid From File",
     *                      type="string",
     *                      example="2024-06-28"
     *                 ),
     *                @OA\Property(
     *                      property="file_valid_until",
     *                      description="Date Valid Until File",
     *                      type="string",
     *                      example="2024-06-28"
     *                ),
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
    public function store(Request $request, StoreDriverHandler $handler, Driver $driver)
    {

        $driver = $handler->process($request);

        if (is_object($driver))
        {
            return $this->success(DriverResource::make($driver));
        }

        return $this->error(message: $driver, code: 401);

    }

    /**
     * @OA\Post(
     *     path="/api/drivers/{driver}",
     *     tags={"Drivers"},
     *     summary="Update Driver Rest API",
     *     description="Update Driver",
     *     operationId="drivers.update",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *           name="driver",
     *           in="path",
     *           description="ID Driver",
     *           required=true,
     *           example=1
     *     ),
     *     @OA\RequestBody(
     *         description="Create user object",
     *         required=true,
     *       @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                    property="gender",
     *                    description="Gender",
     *                    type="integer",
     *                    example=1
     *                ),
     *                @OA\Property(
     *                    property="first_name",
     *                    description="First Name",
     *                    type="string",
     *                    example="Driver Name"
     *                ),
     *                @OA\Property(
     *                      property="surname",
     *                      description="Surname",
     *                      type="string",
     *                      example="Drive Surname"
     *                 ),
     *                @OA\Property(
     *                      property="phone",
     *                      description="Phone",
     *                      type="string",
     *                      example="+380960000000"
     *                 ),
     *                @OA\Property(
     *                      property="email",
     *                      description="Email",
     *                      type="string",
     *                      example="example@hostname.com"
     *                 ),
     *                @OA\Property(
     *                      property="has_license",
     *                      description="License",
     *                      type="integer",
     *                      example=1
     *                 ),
     *                @OA\Property(
     *                      property="languages",
     *                      description="Languages",
     *                      type="array",
     *                      @OA\Items(type="string"),
     *                       example={"RU", "BG", "PL"},
     *                 ),
     *                @OA\Property(
     *                      property="file",
     *                      description="Upload File",
     *                      type="string",
     *                      format="binary",
     *                 ),
     *                @OA\Property(
     *                      property="file_type",
     *                      description="Type File",
     *                      type="integer",
     *                      example=1
     *                  ),
     *                @OA\Property(
     *                      property="file_number",
     *                      description="Number File",
     *                      type="string",
     *                      example="12-RT-54"
     *                 ),
     *                @OA\Property(
     *                      property="file_valid_from",
     *                      description="Date Valid From File",
     *                      type="string",
     *                      example="2024-06-28"
     *                 ),
     *                @OA\Property(
     *                      property="file_valid_until",
     *                      description="Date Valid Until File",
     *                      type="string",
     *                      example="2024-06-28"
     *                ),
     *                @OA\Property(
     *                       property="_method",
     *                       description="Method (should always be PUT)",
     *                       type="string",
     *                       example="PUT"
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
    public function update(Request $request, StoreDriverHandler $handler, Driver $driver)
    {

        $driver = $handler->process($request, $driver);
        if (is_object($driver))
        {
            return $this->success(DriverResource::make($driver));
        }

        return $this->error(message: $driver, code: 401);

    }

    /**
     * @OA\Delete(
     *     path="/api/drivers/{driver}",
     *     tags={"Drivers"},
     *     summary="Delete Driver Rest API",
     *     description="Delete Driver",
     *     operationId="drivers.destroy",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *            name="driver",
     *            in="path",
     *            description="ID Driver",
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
    public function destroy(Request $request, Driver $driver)
    {

        if($driver->delete())
        {
            return $this->success(message: 'Success!');
        }

        return $this->error('', "Error Delete", 401);

    }

}
