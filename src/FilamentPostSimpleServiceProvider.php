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
            ->hasViews()
            ->hasMigration('create_postsimple_settings');
    }

    public function packageBooted(): void
    {
        // Register assets if needed
        FilamentAsset::register([
            Css::make('filament-postsimple-styles', __DIR__ . '/../resources/dist/filament-postsimple.css'),
        ], 'postsimple/filament-postsimple');
    }
}
