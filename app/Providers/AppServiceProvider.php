<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
        // Устанавливаем русскую локаль для Carbon
        \Carbon\Carbon::setLocale('ru');
        
        Gate::policy(\App\Models\TherapySession::class, \App\Policies\SessionPolicy::class);
        Gate::policy(\App\Models\SessionReport::class, \App\Policies\SessionReportPolicy::class);
        Gate::policy(\App\Models\Homework::class, \App\Policies\HomeworkPolicy::class);
    }
}
