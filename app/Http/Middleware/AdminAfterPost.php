<?php

namespace App\Http\Middleware;

use Closure;
use Storage;

class AdminAfterPost
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
      $response = $next($request);
      
      if (env('APP_ENV') != 'local' && $request->isMethod('post')) {
        // log admin activities after POST to s3
        $logString = '[' . date('Ymd:His') . ']' . $request->method() . '@' . $request->fullUrl() . '?' . http_build_query($request->all()) . "\n";
        $logString .= '--------------------------------------------------' . "\n";
        $logFilename = 'admin_' . env('APP_BUILD') . '_' . env('APP_ENV') . '_' . date('Ymd') . '.log';
        Storage::disk(env('EXTENSIVE_LOG_DRIVER', 'local'))->append('storage/logs/' . $logFilename, $logString);
      }
      
      return $response;
    }
}
