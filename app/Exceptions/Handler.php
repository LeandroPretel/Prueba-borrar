<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
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
     * @param Throwable $exception
     * @return void
     *
     * @throws Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TokenInvalidException) {
            return response()->json(['message' => 'Token is invalid'], 401);
        }
        if ($exception instanceof TokenExpiredException) {
            return response()->json(['message' => 'Token has expired'], 401);
        }
        if ($exception instanceof HttpException) {
            return response()->json(['message' => $exception->getMessage()], $exception->getStatusCode());
        }

        return parent::render($request, $exception);
    }
}
