<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

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
        $viteHmrUrl = env('VITE_HMR_URL');

        if ($viteHmrUrl) {
            $viteHotFilePath = storage_path('app/env-hot');

            file_put_contents($viteHotFilePath, $viteHmrUrl);
            Vite::useHotFile($viteHotFilePath);
        }
    }
}
