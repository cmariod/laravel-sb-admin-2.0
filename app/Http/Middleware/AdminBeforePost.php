<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Storage;

class AdminBeforePost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if (env('APP_ENV') != 'local' && ( !empty(Session::get('errors')) || !empty(Session::get('message')) )) {
        // log error & message to s3
        $logString = '[' . date('Ymd:His') . ']' . $request->method() . '@' . $request->fullUrl() . '?' . http_build_query($request->all()) . "\n";
        
        if (!empty(Session::get('errors'))) {
          $logString .= Session::get('errors')->toJSON() . "\n";
        }
        
        if (!empty(Session::get('message'))) {
          $logString .= Session::get('message') . "\n";
        }
        
        $logString .= '--------------------------------------------------' . "\n";
        $logFilename = 'admin_' . env('APP_BUILD') . '_' . env('APP_ENV') . '_' . date('Ymd') . '.log';
        Storage::disk(env('EXTENSIVE_LOG_DRIVER', 'local'))->append('storage/logs/' . $logFilename, $logString);
      }
      
      return $next($request);
    }
}
