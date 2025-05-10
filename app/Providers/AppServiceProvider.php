<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('lecturer.*', function ($view) {
            $count = DB::table('grade_rechecks')->where('status', 'Pending')->count();
            $view->with('recheckCount', $count);
        });
    
    }
}
