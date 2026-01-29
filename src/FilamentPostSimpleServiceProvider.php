<?php

namespace PostSimple\FilamentPostSimple;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentPostSimpleServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-postsimple';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageBooted(): void
    {
        // Publish migration with config tag
        $this->publishes([
            __DIR__ . '/../database/migrations/create_postsimple_settings.php' => database_path('migrations/' . date('Y_m_d_His') . '_create_postsimple_settings.php'),
        ], 'filament-postsimple-config');

        // Register assets if needed
        FilamentAsset::register([
            Css::make('filament-postsimple-styles', __DIR__ . '/../resources/dist/filament-postsimple.css'),
        ], 'postsimple/filament-postsimple');
    }
}
