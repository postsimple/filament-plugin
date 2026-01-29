<?php

namespace PostSimple\FilamentPostSimple\Settings;

use Spatie\LaravelSettings\Settings;

class PostSimpleSettings extends Settings
{
    public string $api_key;

    public static function group(): string
    {
        return 'postsimple';
    }
}
