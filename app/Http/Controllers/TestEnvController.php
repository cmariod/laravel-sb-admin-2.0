<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;

class TestEnvController extends Controller {
  
  public function __construct() {
    // non Prod only middleware
    $this->middleware(function ($request, $next) {
      if (env('APP_ENV') == 'prod') {
        abort(404, 'Page Not Found');
      }
      
      return $next($request);
    });
    
    // AUTH middleware
    $this->middleware(function ($request, $next) {
      if ($request->input('auth') != env('AUTH_TOKEN')) {
        return response()->json(['status' => 1, 'errormsg' => 'Missing or invalid auth token']);
      }
      
      return $next($request);
    });
  }
}