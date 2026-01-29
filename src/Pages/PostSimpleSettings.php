<?php

namespace PostSimple\FilamentPostSimple\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use PostSimple\FilamentPostSimple\Settings\PostSimpleSettings as PostSimpleSettingsClass;

class PostSimpleSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-share';

    protected static string $settings = PostSimpleSettingsClass::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $title = 'PostSimple';

    protected static ?string $navigationLabel = 'PostSimple';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('PostSimple Integration')
                    ->description('Configure your PostSimple API credentials to send content for social media generation.')
                    ->schema([
                        TextInput::make('api_key')
                            ->label('API Key')
                            ->required()
                            ->password()
                            ->revealable()
                            ->helperText('Enter your PostSimple API key. Request one at api@postsimple.nl (Pro subscription required)')
                            ->placeholder('ps_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'),
                    ]),

                Section::make('How to use')
                    ->description('After configuring your API key:')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('instructions')
                            ->content(new \Illuminate\Support\HtmlString('
                                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                    <li>Go to any resource (Posts, Pages, etc.)</li>
                                    <li>Click on a record to view or edit it</li>
                                    <li>Look for the "Send to PostSimple" action button</li>
                                    <li>Click it to send the content to PostSimple</li>
                                    <li>You\'ll be redirected to PostSimple to view the generated social media content</li>
                                </ol>
                                <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <p class="text-sm text-blue-800 dark:text-blue-300">
                                        <strong>Note:</strong> The PostSimple API is only available with a Pro subscription. 
                                        Don\'t have one yet? Visit <a href="https://postsimple.app" target="_blank" class="underline">postsimple.app</a>
                                    </p>
                                </div>
                            ')),
                    ])
                    ->collapsible(),
            ]);
    }
}
