<?php

namespace PostSimple\FilamentPostSimple;

use Filament\Support\Facades\FilamentView;
use Illuminate\Support\ServiceProvider;

class FilamentPostSimpleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/filament-postsimple.php', 'filament-postsimple');
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-postsimple');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-postsimple');

        if (class_exists(FilamentView::class) && method_exists(FilamentView::class, 'registerRenderHook')) {
            FilamentView::registerRenderHook(
                'panels::body.end',
                fn (): string => view('filament-postsimple::open-url-listener')->render()
            );
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/filament-postsimple.php' => config_path('filament-postsimple.php'),
            ], 'filament-postsimple-config');

            $this->publishes([
                __DIR__ . '/../resources/lang' => lang_path('vendor/filament-postsimple'),
            ], 'filament-postsimple-lang');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-postsimple'),
            ], 'filament-postsimple-views');
        }
    }
}
