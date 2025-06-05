<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use our custom pagination view by default
        Paginator::defaultView('vendor.pagination.argon');
        Paginator::defaultSimpleView('vendor.pagination.simple-argon');
    }
}
