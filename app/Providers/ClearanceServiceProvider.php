<?php

namespace App\Providers;

use App\Helpers\Clearance;
use Illuminate\Support\ServiceProvider;

class ClearanceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'clearance',
            function () {
                return new Clearance();
            }
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
