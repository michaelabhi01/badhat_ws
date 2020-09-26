<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

// use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        // checks if the route is from api the, throw json error response
        if ($request->is('api/*')) {
            if ($exception instanceof TokenExpiredException) {
                return response()->json(['token_expired'], $exception->getStatusCode());
            } else if ($exception instanceof TokenInvalidException) {
                return response()->json(['token_invalid'], $exception->getStatusCode());
            } else {
                return response()->json([
                    'error_message' => $exception->getMessage(),
                    'status' => Response::HTTP_BAD_REQUEST,
                ]);
            }

        }

        // for web routes, use redirect
        if ($this->isHttpException($exception)) {
            if ($exception->getstatusCode() == 404) {
                return redirect(route('login')); //redirect to login if entered url is not valid
            }
        }

        return parent::render($request, $exception);
    }
}
