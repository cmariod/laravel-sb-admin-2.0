<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function() { return ''; });

Route::group(['middleware' => ['csrf']], function () {
  // Authentication Routes...
  Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
  Route::post('login', 'Auth\LoginController@login');
  Route::post('logout', 'Auth\LoginController@logout')->name('logout');

  // Registration Routes...
  // Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
  // Route::post('register', 'Auth\RegisterController@register');

  // Password Reset Routes...
  // Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
  // Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
  // Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
  // Route::post('password/reset', 'Auth\ResetPasswordController@reset');

  Route::group(['prefix' => env('APP_ADMIN_PATH'), 'middleware' => ['auth']], function () {
    Route::get('/', 'Admin\HomeController@index')->name('admin');
    
    // user management routing
    Route::get('/user', 'Admin\UserController@adminIndex')->name('user');
    Route::get('/user/new', 'Admin\UserController@showNewForm')->name('user/new');
    Route::post('/user/new', 'Admin\UserController@create');
    Route::get('/user/edit/{id}', 'Admin\UserController@showEditForm')->name('user/edit')->where([
      'id' => '[0-9]+'
    ]);
    Route::post('/user/edit/{id}', 'Admin\UserController@edit');
    Route::get('/user/delete/{id}', 'Admin\UserController@delete')->name('user/delete')->where([
      'id' => '[0-9]+'
    ]);
      
    // User management routing
    Route::get('/report', 'Admin\ReportController@index')->name('report');
    Route::post('/report', 'Admin\ReportController@generate');
    
  });
});

Route::group(['prefix' => 'sbadmin', 'middleware' => ['auth']], function () {
  Route::get('/', function() { return view('sbadmin.home'); });
  Route::get('/charts', function() { return view('sbadmin.mcharts'); });
  Route::get('/tables', function() { return view('sbadmin.table'); });
  Route::get('/forms', function() { return view('sbadmin.form'); });
  Route::get('/grid', function() { return view('sbadmin.grid'); });
  Route::get('/buttons', function() { return view('sbadmin.buttons'); });
  Route::get('/icons', function() { return view('sbadmin.icons'); });
  Route::get('/panels', function() { return view('sbadmin.panel'); });
  Route::get('/typography', function() { return view('sbadmin.typography'); });
  Route::get('/notifications', function() { return view('sbadmin.notifications'); });
  Route::get('/blank', function() { return view('sbadmin.blank'); });
  Route::get('/documentation', function() { return view('sbadmin.documentation'); });
});

Route::group(['prefix' => 'api'], function () {
  
  Route::get('/', 'HealthCheckController@index');
  Route::get('/healthcheck', 'HealthCheckController@extensive');
    
  // test env (non-prod) only api
  
  Route::group(['middleware' => ['api.auth']], function () { // everything inside needs static auth token
    
  });
  
});
Route::group(['prefix' => 'report'], function () {
  
  Route::get('/', 'HealthCheckController@index');
  
  // this routing is commented until we need special reporting
  // Route::match(['get', 'post'], 'rubish.{extension}', 'ReportController@generateCustomReport')
  // ->where([
  //   'extension' => 'json|csv|xls|xlsx'
  // ]);
  
  Route::match(['get', 'post'], '{reportName}.{extension}', 'Admin\ReportController@generateStandardReport')
  ->where([
    'reportName' => '[A-Za-z_\-\(\)0-9]+',
    'extension' => 'json|csv|xls|xlsx'
  ]);
  
});
