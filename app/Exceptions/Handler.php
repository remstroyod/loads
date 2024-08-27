<?php

namespace App\Exceptions;

use App\Traits\HttpResponses;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{

    use HttpResponses;
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {

        $this->renderable(function (AuthenticationException $e, $request)
        {

            if ($request->is('api/*'))
            {
                return $this->error([],"Unauthenticated.",401);
            }

        });

        $this->renderable(function (ModelNotFoundException $e, $request)
        {

            if ( $request->is('api/*') )
            {
                return response()->json([
                    'message' => $e->getMessage()
                ], 404);
            }

        });

        $this->renderable(function (NotFoundHttpException $e, $request)
        {

            if ( $request->is('api/*') )
            {
                return $this->error([], $e->getMessage(),401);
            }

        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request)
        {

            if ( $request->is('api/*') )
            {
                return $this->error([],"Method Not Allowed",404);
            }

        });

        $this->renderable(function (AccessDeniedHttpException $e, $request)
        {

            if ( $request->is('api/*') )
            {
                return $this->error([],$e->getMessage(),404);
            }

        });

        $this->renderable(function (RouteNotFoundException $e, $request)
        {

            if ( $request->is('api/*') )
            {
                return $this->error([], $e->getMessage(), 401);
            }

        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
