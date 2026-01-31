<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;

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
        Paginator::useBootstrap();
        App::setLocale('fr');

        // Share pending withdrawals count with admin layout
        view()->composer('layouts.admin', function ($view) {
            $pendingRetraitsCount = 0;
            // Best effort check for table existence to avoid migration errors on fresh install
            // But usually safe in production.
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('retraits')) {
                     $pendingRetraitsCount = \App\Models\Retrait::where('statut', 'EN_ATTENTE')->count();
                }
            } catch (\Exception $e) {
                // Ignore if DB not ready
            }
            $view->with('pendingRetraitsCount', $pendingRetraitsCount);
        });
    }
}
