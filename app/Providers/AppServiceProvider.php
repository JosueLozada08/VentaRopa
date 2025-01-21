<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\NotificationService;
use App\Services\SmsNotificationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(NotificationService::class, SmsNotificationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }


    
}
