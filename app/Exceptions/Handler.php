<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Storage;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
      if (env('APP_ENV') != 'local') {
        // send exception to s3
        $logString = '[' . date('Ymd:His') . ']' . $request->method() . '@' . $request->fullUrl() . '?' . http_build_query($request->all()) . "\n";
        $logString .= $exception->getTraceAsString() . "\n";
        $logString .= '--------------------------------------------------' . "\n";
        $logFilename = 'error_' . env('APP_BUILD') . '_' . env('APP_ENV') . '_' . date('YmdH') . '.log';
        Storage::disk(env('EXTENSIVE_LOG_DRIVER', 'local'))->append('storage/logs/' . $logFilename, $logString);
      }
      
      // custom exception for api
      if (in_array($request->segment(1), ['api', 'elbs'])) {
        if ($exception instanceof \Illuminate\Foundation\Http\Exceptions\MaintenanceModeException) {
          return response()->json(["status" => 503, "errormsg" => "server maintenance"], 503);
        }
        
        $exception = $this->prepareException($exception);
        return response()->json(["exception" => $exception->getMessage() . ((env('APP_ENV') != 'prod')? '@'.$exception->getFile().':'.$exception->getLine() : '')]);
      }
      
      return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
