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
        FilamentAsset::register([
            Css::make('filament-postsimple-styles', __DIR__ . '/../resources/dist/filament-postsimple.css'),
        ], 'postsimple/filament-postsimple');
    }
}
