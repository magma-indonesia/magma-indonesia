<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        if ($request->ajax() || $request->wantsJson()) {

            if ($exception instanceof TokenMismatchException)
            {
                return response()->json([
                        'status' => 'false',
                        'code' => 419,
                        'message' => 'Invalid Token'], 419);
            }

            if ($exception instanceof UnauthorizedHttpException) {

                if ($exception->getPrevious() instanceof TokenExpiredException)
                    return $this->ApiException($exception->getStatusCode(), 'Token Expired');
                
                if ($exception->getPrevious() instanceof TokenInvalidException)
                    return $this->ApiException($exception->getStatusCode(), 'Token Invalid');
                
                if ($exception->getPrevious() instanceof TokenBlacklistedException)
                    return $this->ApiException($exception->getStatusCode(), 'Token Blacklisted');
                
            }

            return $exception instanceof ModelNotFoundException ? 
                $this->ApiException(404, 'Data tidak ditemukan.') :
                $this->ApiException(400, 'Bad request.');

        }

        return parent::render($request, $exception);
    }

    /**
     * Undocumented function
     *
     * @param int $code
     * @param string $message
     * @return json
     */
    protected function ApiException(int $code, string $message)
    {
        return response()->json([
            'status' => 'false',
            'code' => $code,
            'message' => $message], $code);
    }
}
