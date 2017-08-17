<?php

namespace App\Providers;

use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Queue::failing(function (JobFailed $event) {
        $payload = [
          'username' => 'Laravel-' . env('APP_BUILD') . '-' . env('APP_ENV'), 
          'text' => $event->exception->getMessage() ];
        postRequest(env('LARAVEL_WEBHOOK_URL'), json_encode($payload));
      });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
