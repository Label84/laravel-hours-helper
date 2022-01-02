<?php

namespace Label84\HoursHelper;

use Illuminate\Support\ServiceProvider;

class HoursHelperServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(HoursHelper::class, function ($app) {
            return new HoursHelper();
        });
    }

    public function boot(): void
    {
        //
    }
}
