<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('components.home-layout', function ($view) {
            if (auth()->check()) {
                $currentDate = Carbon::now();
                $view->with('calendarDate', $currentDate);
            }
        });
    }
}
