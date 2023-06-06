<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.https')) {
            URL::forceScheme('https');
        }
        Schema::defaultStringLength(191);

        $versionIdentifier = '';
        if (File::exists(base_path('.version'))) {
            $versionIdentifier = substr(File::get(base_path('.version')), 0, 7);
        }

        view()->share('versionIdentifier', $versionIdentifier);

        Paginator::useBootstrap();
    }
}
