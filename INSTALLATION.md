# Installation Guide

## Quick Start

### 1. Install via Composer

```bash
composer require postsimple/filament-postsimple
```

### 2. Install Spatie Laravel Settings

If you don't already have it installed:

```bash
composer require spatie/laravel-settings
```

### 3. Publish and Run Migrations

```bash
php artisan vendor:publish --tag="filament-postsimple-settings"
php artisan migrate
```

### 4. Register the Plugin

In your Filament Panel Provider (e.g., `app/Providers/Filament/AdminPanelProvider.php`):

```php
use PostSimple\FilamentPostSimple\FilamentPostSimplePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentPostSimplePlugin::make(),
        ]);
}
```

### 5. Get Your API Key

1. Ensure you have a PostSimple Pro subscription
2. Email api@postsimple.nl with:
   - Your company name
   - Your PostSimple account email
   - Your website URL

### 6. Configure

1. Go to your Filament admin: **Settings → PostSimple**
2. Enter your API key
3. Save

### 7. Add to Resources

Add the action to any resource:

```php
use PostSimple\FilamentPostSimple\Actions\SendToPostSimpleTableAction;

public static function table(Table $table): Table
{
    return $table
        ->actions([
            SendToPostSimpleTableAction::make(),
        ]);
}
```

That's it! You're ready to send content to PostSimple! 🎉
