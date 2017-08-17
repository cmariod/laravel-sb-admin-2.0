<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Storage;

class RequestLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
      // don't log local test env and empty request to root path (for aws health check)
      if (env('APP_ENV') != 'local' && $request->path() != '/') {
        // send exception to s3
        $logString = '[' . date('Ymd:His') . ']' . $request->method() . '@' . $request->fullUrl() . '?' . http_build_query($request->all()) . "\n";
        $logFilename = 'access_' . env('APP_BUILD') . '_' . env('APP_ENV') . '_' . date('YmdH') . '.log';
        Storage::disk(env('EXTENSIVE_LOG_DRIVER', 'local'))->append('storage/logs/' . $logFilename, $logString);
      }
      
      return $next($request);
    }
}
