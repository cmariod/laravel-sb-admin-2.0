<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Storage;

class APIStaticAuth
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
        if ($request->input('auth') != env('AUTH_TOKEN')) {
          $logString = '[' . date('Ymd:His') . ']' . $request->method() . '@' . $request->fullUrl() . '?' . http_build_query($request->all()) . "\n";
          $logString .= json_encode(['status' => 1, 'errormsg' => 'Missing or invalid auth token']) . "\n";
          $logFilename = 'error_' . env('APP_BUILD') . '_' . env('APP_ENV') . '_' . date('YmdH') . '.log';
          Storage::disk(env('EXTENSIVE_LOG_DRIVER', 'local'))->append('storage/logs/' . $logFilename, $logString);
          
          return response()->json(['status' => 1, 'errormsg' => 'Missing or invalid auth token']);
        }
        
        return $next($request);
    }
}
