<?php

namespace App\Providers;

use App\Helpers\AppConfig;
use App\Models\MutasiBarang;
use App\Observers\MutasiBarangObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use View;

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
        MutasiBarang::observe(MutasiBarangObserver::class);
        
        \Illuminate\Support\Facades\Validator::extend('nrp', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[0-9]{6}$/', $value);
        }, 'NRP harus terdiri dari 6 digit angka.');
        Paginator::defaultView('vendor.pagination.default');
        View::share('appConfig', AppConfig::all());
    }
}
