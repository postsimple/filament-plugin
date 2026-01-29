# PostSimple for Filament

[![Latest Version on Packagist](https://img.shields.io/packagist/v/postsimple/filament-postsimple.svg?style=flat-square)](https://packagist.org/packages/postsimple/filament-postsimple)
[![Total Downloads](https://img.shields.io/packagist/dt/postsimple/filament-postsimple.svg?style=flat-square)](https://packagist.org/packages/postsimple/filament-postsimple)

Send content from your Filament admin panel to [PostSimple](https://postsimple.app) with one click to automatically generate professional social media content powered by AI.

## About PostSimple

[PostSimple](https://postsimple.app) is an AI-powered social media tool that automatically creates and schedules social media posts tailored to your brand's style across all channels. This Filament plugin allows you to seamlessly send content from your Filament resources to PostSimple for instant social media content generation.

## Features

- ✅ **One-click integration** - Send any resource to PostSimple with a single click
- ✅ **Easy configuration** - Simple API key setup in Filament settings
- ✅ **Automatic detection** - Intelligently finds title and URL from your models
- ✅ **Works everywhere** - Add to any Filament resource (Posts, Pages, Products, etc.)
- ✅ **Seamless redirect** - Automatically redirects to PostSimple to view generated content
- ✅ **Secure** - API key stored securely in settings

## Requirements

- PHP 8.1 or higher
- Laravel 10.0 or higher
- Filament 3.x, 4.x, or 5.x
- **PostSimple Pro subscription** with API access

> **Note:** The PostSimple API is only available with a Pro subscription. Request your API key at [api@postsimple.nl](mailto:api@postsimple.nl)

## Installation

Install the package via Composer:

```bash
composer require postsimple/filament-postsimple
```

Install Spatie Laravel Settings (if not already installed):

```bash
composer require spatie/laravel-settings
```

Publish the config file and migration, then run the migration:

```bash
php artisan vendor:publish --tag="filament-postsimple-config"
php artisan migrate
```

## Configuration

### 1. Register the Plugin

Add the plugin to your Filament panel provider (e.g., `app/Providers/Filament/AdminPanelProvider.php`):

```php
use PostSimple\FilamentPostSimple\FilamentPostSimplePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugins([
            FilamentPostSimplePlugin::make(),
        ]);
}
```

### 2. Get Your API Key

1. Ensure you have a **PostSimple Pro subscription**
2. Send an email to **api@postsimple.nl** with:
   - Your company name
   - Your PostSimple account email
   - Your website URL
3. You'll receive your API key within 1-2 business days

### 3. Configure the Plugin

1. Go to your Filament admin panel
2. Navigate to **Settings → PostSimple**
3. Enter your API key
4. Click **Save**

## Usage

### Adding the Action to Resources

You can add the PostSimple action to any Filament resource in two ways:

#### Option 1: In the Resource View/Edit Page (Header Actions)

Add to your resource's `ViewRecord` or `EditRecord` page:

```php
use PostSimple\FilamentPostSimple\Actions\SendToPostSimpleAction;

protected function getHeaderActions(): array
{
    return [
        SendToPostSimpleAction::make(),
        // ... other actions
    ];
}
```

#### Option 2: In the Resource Table (Row Actions)

Add to your resource's table:

```php
use PostSimple\FilamentPostSimple\Actions\SendToPostSimpleTableAction;

public static function table(Table $table): Table
{
    return $table
        ->columns([
            // ... your columns
        ])
        ->actions([
            SendToPostSimpleTableAction::make(),
            // ... other actions
        ]);
}
```

### Example: Blog Post Resource

Here's a complete example of adding PostSimple to a blog post resource:

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use PostSimple\FilamentPostSimple\Actions\SendToPostSimpleTableAction;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                SendToPostSimpleTableAction::make(), // Add this line
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
```

And in your `EditPost` page:

```php
<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use PostSimple\FilamentPostSimple\Actions\SendToPostSimpleAction;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            SendToPostSimpleAction::make(), // Add this line
        ];
    }
}
```

### How It Works

1. Click the **"Send to PostSimple"** button on any record
2. Confirm the action in the modal
3. The plugin sends the title and URL to PostSimple
4. You're automatically redirected to PostSimple to view the generated content

## Model Requirements

The plugin works best when your models follow certain conventions, but it's flexible enough to work with any structure.

### Automatic Title Detection

The plugin automatically looks for these fields (in order):
1. Custom field (if you specify with `->titleAttribute()`)
2. `title`
3. `name`
4. `heading`
5. `subject`
6. Falls back to `ModelName #ID`

**Example:**
```php
// Works automatically - has 'title' field
class Post extends Model {
    protected $fillable = ['title', 'content'];
}

// Works automatically - has 'name' field  
class Product extends Model {
    protected $fillable = ['name', 'price'];
}

// Needs customization - has different field
class Article extends Model {
    protected $fillable = ['headline', 'body'];
}
// Use: ->titleAttribute('headline')
```

### Automatic URL Detection

The plugin tries to get the URL in this order:
1. Custom closure (if you specify with `->urlUsing()`)
2. `$record->getUrl()` method
3. `$record->url()` method
4. Builds from `slug` field: `/table-name/{slug}`
5. Falls back to: `/table-name/{id}`

**Example with getUrl() method:**
```php
class Post extends Model
{
    protected $fillable = ['title', 'slug', 'content'];

    // Add this method to your model
    public function getUrl(): string
    {
        return url('/blog/' . $this->slug);
    }
}
```

**Example with route helper:**
```php
class Product extends Model
{
    public function getUrl(): string
    {
        return route('products.show', $this);
    }
}
```

### When Customization is Required

You **need** to customize when:
- Your title field has a non-standard name (not `title`, `name`, `heading`, or `subject`)
- You need a specific URL format that can't be auto-detected
- You're working with a headless frontend (different domain)

**Product example with customization:**
```php
SendToPostSimpleTableAction::make()
    ->titleAttribute('product_name')
    ->urlUsing(fn ($record) => 'https://shop.example.com/products/' . $record->sku)
```

## Customization

### Custom Title Field

If your model uses a different field for the title, you can specify it:

```php
SendToPostSimpleAction::make()
    ->titleAttribute('product_name') // Use 'product_name' instead of 'title'
```

### Custom URL

You can provide a custom closure to generate the URL:

```php
SendToPostSimpleTableAction::make()
    ->urlUsing(fn ($record) => route('shop.product', $record))
```

### Complete Customization Example

```php
use PostSimple\FilamentPostSimple\Actions\SendToPostSimpleTableAction;

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('product_name'),
            Tables\Columns\TextColumn::make('sku'),
        ])
        ->actions([
            SendToPostSimpleTableAction::make()
                ->titleAttribute('product_name') // Custom title field
                ->urlUsing(fn ($record) => route('shop.product', $record->slug)), // Custom URL
        ]);
}
```

### Using Both Title and URL Customization

```php
SendToPostSimpleAction::make()
    ->titleAttribute('custom_heading')
    ->urlUsing(function ($record) {
        return config('app.frontend_url') . '/posts/' . $record->id;
    })
```

## Configuration Options

The plugin publishes a config file with the following options:

```php
return [
    // API endpoint (don't change unless instructed)
    'api_endpoint' => env('POSTSIMPLE_API_ENDPOINT', 'https://postsimple.link/api/plugins/create-post'),

    // PostSimple app URL
    'app_url' => env('POSTSIMPLE_APP_URL', 'https://my.postsimple.app/'),

    // Request timeout (seconds)
    'timeout' => env('POSTSIMPLE_TIMEOUT', 30),
];
```

You can override these in your `.env` file:

```env
POSTSIMPLE_API_ENDPOINT=https://postsimple.link/api/plugins/create-post
POSTSIMPLE_APP_URL=https://my.postsimple.app/
POSTSIMPLE_TIMEOUT=30
```

## Troubleshooting

### "PostSimple API key not configured"
- Go to **Settings → PostSimple** and enter your API key
- Make sure you've saved the settings

### "No title found"
- Ensure your model has a `title`, `name`, `heading`, or `subject` field
- Or add a custom getter method

### "No URL found"
- Add a `getUrl()` or `url()` method to your model
- Or ensure your model has a `slug` field

### "Failed to send to PostSimple"
- Check that your API key is correct
- Verify you have an active Pro subscription
- Check your internet connection
- View the error message for more details

## Support

For questions or issues:

- **Email:** contact@postsimple.nl
- **Website:** [https://postsimple.app](https://postsimple.app)
- **Support:** [https://postsimple.app/support](https://postsimple.app/support)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Security

If you discover any security-related issues, please email contact@postsimple.nl instead of using the issue tracker.

## Credits

- [PostSimple](https://postsimple.app)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
