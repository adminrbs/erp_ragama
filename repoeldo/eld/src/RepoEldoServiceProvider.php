<?php

namespace RepoEldo\ELD;

use Illuminate\Support\ServiceProvider;

class RepoEldoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->make('RepoEldo\ELD\ReportController');
        $this->loadViewsFrom(__DIR__.'/views','eldo_report');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        include __DIR__ . '/routes.php';
    }
}
