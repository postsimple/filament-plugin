<?php

namespace PostSimple\FilamentPostSimple;

use Illuminate\Support\ServiceProvider;

class FilamentPostSimpleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/filament-postsimple.php', 'filament-postsimple');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/filament-postsimple.php' => config_path('filament-postsimple.php'),
            ], 'filament-postsimple-config');
        }
    }
}
