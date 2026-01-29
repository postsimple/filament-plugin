<?php

namespace PostSimple\FilamentPostSimple;

use Filament\Contracts\Plugin;
use Filament\Panel;
use PostSimple\FilamentPostSimple\Pages\PostSimpleSettings;

class FilamentPostSimplePlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-postsimple';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->pages([
                PostSimpleSettings::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }
}
