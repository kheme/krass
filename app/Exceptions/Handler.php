<?php

namespace App\Exceptions;

use App\Http\Helpers\Helper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use DB;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        DB::rollback();

        $default_code  = 500;
        $error_message = $exception->getMessage();
        $status_code   = $exception->getCode() ?? $default_code;
        
        if (method_exists($exception, 'errors')) {
            $status_code = 400;
            foreach ($exception->errors() as $field => $message) {
                $error_message = "$field: " . $message[0];
                break;
            }
        }

        if ($exception instanceof ModelNotFoundException) {
            $error_message = str_replace('App\\Models\\', '', $exception->getModel()) . ' not found!';
            $status_code   = 404;
        }

        if (strpos($error_message, '(SQL') !== false) {
            $error_message = trim(substr($error_message, 0, strpos($error_message, '(SQL')));
        }
        
        return Helper::errorResponse($error_message, $status_code == 0 ? $default_code : $status_code);
    }
}
