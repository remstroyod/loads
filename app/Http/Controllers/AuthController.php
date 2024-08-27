<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AuthUserRequest;
use App\Http\Requests\Auth\CheckUserRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\Country;
use App\Models\User;
use App\Traits\FileTrait;
use App\Traits\HttpResponses;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{

    use FileTrait;

    use HttpResponses;

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Register User Rest API",
     *     description="Register User",
     *     operationId="auth.register",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         description="Create user object",
     *         required=true,
     *       @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                     property="name",
     *                     description="User Name",
     *                     type="string",
     *                     example="Name"
     *                 ),
     *                @OA\Property(
     *                     property="surname",
     *                     description="User Surname",
     *                     type="string",
     *                     example="Surname"
     *                 ),
     *                @OA\Property(
     *                     property="email",
     *                     description="User Email",
     *                     type="string",
     *                     example="admin@example.com"
     *                ),
     *                @OA\Property(
     *                    property="password",
     *                    description="User Password",
     *                    type="string",
     *                    example=123456
     *                ),
     *                @OA\Property(
     *                    property="password_confirmation",
     *                    description="User Password Confirmation",
     *                    type="string",
     *                    example=123456
     *                 ),
     *                @OA\Property(
     *                    property="position_id",
     *                    description="User Position",
     *                    type="integer",
     *                    example=1
     *                 ),
     *                @OA\Property(
     *                    property="gender",
     *                    description="User Gender",
     *                    type="integer",
     *                    example=1
     *                  ),
     *                @OA\Property(
     *                    property="company_name",
     *                    description="User Company",
     *                    type="string",
     *                    example="My Company"
     *                 ),
     *                @OA\Property(
     *                    property="street",
     *                    description="User Street",
     *                    type="string",
     *                    example="Street Name"
     *                 ),
     *                @OA\Property(
     *                    property="post",
     *                    description="User Post",
     *                    type="string",
     *                    example="65000"
     *                 ),
     *                @OA\Property(
     *                    property="city",
     *                    description="User City",
     *                    type="string",
     *                    example="Odessa"
     *                 ),
     *                @OA\Property(
     *                    property="country_id",
     *                    description="User Country",
     *                    type="integer",
     *                    example="Odessa"
     *                 ),
     *                @OA\Property(
     *                    property="salutation",
     *                    description="User Salutation",
     *                    type="string",
     *                    example="Salutation"
     *                 ),
     *                @OA\Property(
     *                     property="phone",
     *                     description="User Phone",
     *                     type="string",
     *                     example="+380960000000"
     *                 ),
     *                @OA\Property(
     *                     property="confirm_docs",
     *                     description="User Confirm Docs",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                @OA\Property(
     *                     property="subcontractors",
     *                     description="User Subcontractors",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                @OA\Property(
     *                      property="trailers",
     *                      description="User Trailers",
     *                      type="object",
     *                      @OA\AdditionalProperties(
     *                          type="integer",
     *                          description="The Trailer ID as key and Count as value"
     *                      ),
     *                      example={"1": 2, "2": 4}
     *                ),
     *                @OA\Property(
     *                       property="tractors",
     *                       description="User Tractors",
     *                       type="object",
     *                       @OA\AdditionalProperties(
     *                           type="integer",
     *                           description="The Tractors ID as key and Count as value"
     *                       ),
     *                       example={"1": 1, "2": 3, "3": 6}
     *                ),
     *                @OA\Property(
     *                        property="miscellaneous",
     *                        description="User Miscellaneous",
     *                        type="object",
     *                        @OA\AdditionalProperties(
     *                            type="integer",
     *                            description="The Miscellaneous ID as key and Count as value"
     *                        ),
     *                        example={"1": 7}
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
    public function register(RegisterUserRequest $request)
    {

        if($request->filled('country_id'))
        {
            $country = Country::where('iso', $request->get('country_id'))->first();
            $request->merge(['country_id' => $country ? $country->id : 1]);
        }

        $user = User::create($request->all());

        $this->syncRelatedData($user, $request, 'trailers');
        $this->syncRelatedData($user, $request, 'tractors');
        $this->syncRelatedData($user, $request, 'miscellaneous');

        //Files
        $this->syncFiles($request, $user);
        //End Files

        $accessToken = $user->createToken('authToken')->plainTextToken;

        event(new Registered($user));

        Log::debug('Create New User');

        return $this->success([
            'user' => UserResource::make($user),
            'token' => $accessToken,
        ], "User created Successfully...!");

    }

    /**
     * @OA\Post(
     *     path="/api/register/upload-file",
     *     tags={"Auth"},
     *     summary="Register File Upload User Rest API",
     *     description="Register File Upload User",
     *     operationId="auth.register.upload",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         description="Create user object",
     *         required=true,
     *       @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *                type="object",
     *               @OA\Property(
     *                     property="file",
     *                     description="File Passport",
     *                     type="string",
     *                     format="binary"
     *               ),
     *              @OA\Property(
     *                      property="filename",
     *                      description="File Type",
     *                      type="string",
     *                      example="passport or cmr or license"
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
    public function upload(Request $request)
    {

        if($request->hasFile('file') && $request->has('filename')) {

            $type = $request->get('filename');
            $sessionId = $request->ip();
            $cacheKey = "guest_{$sessionId}_files";

            $data = [];

            $default = Cache::get($cacheKey, []);

            if ($default) {
                $data = $default;
            }

            $data['files'][$type] = $this->fileUpload($request, $type);

            Cache::put($cacheKey, $data, now()->addMinutes(30));

            $default = Cache::get($cacheKey);

            return $this->success($default, "File Upload Successfully...!");

        }

        return $this->error(message: 'file and filename is required');

    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Login User Rest API",
     *     description="Login User",
     *     operationId="auth.login",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         description="Create user object",
     *         required=true,
     *       @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                    property="email",
     *                    description="User Email",
     *                    type="string",
     *                    example="admin@example.com"
     *                ),
     *                @OA\Property(
     *                    property="password",
     *                    description="User Password",
     *                    type="string",
     *                    example=123456
     *                ),
     *              @OA\Property(
     *                     property="remember",
     *                     description="User Remember Me",
     *                     type="boolean",
     *                     example=true
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
    public function login(AuthUserRequest $request)
    {

        if(!Auth::attempt($request->only(['email', 'password']), $request->has('remember'))) {
            return $this->error(message: 'Invalid Credentials');
        }

        $user = Auth::user();
        $accessToken = $user->createToken('authToken')->plainTextToken;

        return $this->success([
            'user'  => UserResource::make($user),
            'token' => $accessToken
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Logout User Rest API",
     *     description="Logout User",
     *     operationId="auth.logout",
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
    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return $this->success(message: "User logout successfully...!");
    }

    /**
     * @OA\Post(
     *     path="/api/register/check-user",
     *     tags={"Auth"},
     *     summary="Check User Rest API",
     *     description="Check User",
     *     operationId="auth.register.checkuser",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         description="Create user object",
     *         required=true,
     *       @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                   property="email",
     *                   description="User Email",
     *                   type="string",
     *                   example="admin@example.com"
     *                ),
     *                @OA\Property(
     *                   property="password",
     *                   description="User Password",
     *                   type="string",
     *                   example=123456
     *                ),
     *                @OA\Property(
     *                   property="password_confirmation",
     *                   description="User Password Confirmation",
     *                   type="string",
     *                   example=123456
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
    public function checkUser(CheckUserRequest $request)
    {
        return $this->success(message: "Success!");
    }

    private function syncRelatedData(User $user, Request $request, $attribute): void
    {

        if($request->has($attribute))
        {

            if(is_array($request->get($attribute)))
            {
                $data = json_decode(json_encode($request->get($attribute)));
            }else{
                $data = json_decode($request->get($attribute));
            }

            $collect = collect($data);
            $data = [];

            $collect->each(function ($item, $key) use (&$data) {
                $data[$key] = ['count' => $item];
            });

            $user->$attribute()->sync($data);
        }
    }

    private function syncFiles(Request $request, User $user): void
    {
        $sessionId = $request->ip();
        $cacheKey = "guest_{$sessionId}_files";
        $default = Cache::get($cacheKey, []);
        if($default)
        {
            $files = collect($default['files']);
            $files->each(function ($item, $key) use ($user) {
                $user->files()->create($item);
            });
        }
        Cache::forget($cacheKey);

    }

    /**
     * @OA\Post(
     *     path="/api/forgot-password",
     *     tags={"Auth"},
     *     summary="Forgot Password Rest API",
     *     description="Forgot Password",
     *     operationId="auth.forgot-password",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         description="Create user object",
     *         required=true,
     *       @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                   property="email",
     *                   description="User Email",
     *                   type="string",
     *                   example="admin@example.com"
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
    public function forgotPassword(ForgotPasswordRequest $request)
    {

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? $this->success(message: __($status))
            : $this->error(message: __($status));
    }

    /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     tags={"Auth"},
     *     summary="Reset Password Rest API",
     *     description="Reset Password",
     *     operationId="auth.reset-password",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         description="Create user object",
     *         required=true,
     *       @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                   property="email",
     *                   description="User Email",
     *                   type="string",
     *                   example="admin@example.com"
     *                ),
     *                @OA\Property(
     *                    property="token",
     *                    description="Token",
     *                    type="string",
     *                    example="906d2c7f9247682253cfaef92e39761274e62038848d2a9088687c9896706552"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Password",
     *                     type="string",
     *                     example="912345678"
     *                  ),
     *                 @OA\Property(
     *                      property="password_confirmation",
     *                      description="Password Confirmation",
     *                      type="string",
     *                      example="912345678"
     *                   ),
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
    public function resetPassword(ResetPasswordRequest $request)
    {

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                $user->tokens()->delete();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return $this->success(message: __($status));
        }

        return $this->error(message: __($status));

    }

}
