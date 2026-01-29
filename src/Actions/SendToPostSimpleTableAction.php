<?php

namespace PostSimple\FilamentPostSimple\Actions;

use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use PostSimple\FilamentPostSimple\Settings\PostSimpleSettings;

class SendToPostSimpleTableAction extends Action
{
    protected string $apiEndpoint = 'https://postsimple.link/api/plugins/create-post';
    
    protected string $postSimpleUrl = 'https://my.postsimple.app/';
    
    protected ?string $titleAttribute = null;
    
    protected ?\Closure $urlUsing = null;

    public static function getDefaultName(): ?string
    {
        return 'sendToPostSimple';
    }
    
    /**
     * Set which attribute to use for the title
     */
    public function titleAttribute(string $attribute): static
    {
        $this->titleAttribute = $attribute;
        
        return $this;
    }
    
    /**
     * Set a custom closure to get the URL
     */
    public function urlUsing(\Closure $callback): static
    {
        $this->urlUsing = $callback;
        
        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Send to PostSimple');

        $this->icon('heroicon-o-share');

        $this->color('primary');

        $this->requiresConfirmation();

        $this->modalHeading('Send to PostSimple');

        $this->modalDescription('This will send the title and URL of this record to PostSimple to generate social media content.');

        $this->modalSubmitActionLabel('Send to PostSimple');

        $this->action(function (Model $record) {
            try {
                // Get settings
                $settings = app(PostSimpleSettings::class);
                
                if (empty($settings->api_key)) {
                    Notification::make()
                        ->title('PostSimple API key not configured')
                        ->body('Please configure your API key in the PostSimple settings page.')
                        ->danger()
                        ->send();
                    
                    return;
                }

                // Get title - try common title fields
                $title = $this->getRecordTitle($record);
                
                if (empty($title)) {
                    Notification::make()
                        ->title('No title found')
                        ->body('This record doesn\'t have a title field. Cannot send to PostSimple.')
                        ->danger()
                        ->send();
                    
                    return;
                }

                // Get URL - try to build from route
                $url = $this->getRecordUrl($record);
                
                if (empty($url)) {
                    Notification::make()
                        ->title('No URL found')
                        ->body('Cannot determine the public URL for this record.')
                        ->danger()
                        ->send();
                    
                    return;
                }

                // Make API request
                $response = Http::withHeaders([
                    'X-API-PostSimple-Key' => $settings->api_key,
                ])
                    ->timeout(30)
                    ->post($this->apiEndpoint, [
                        'title' => $title,
                        'url' => $url,
                    ]);

                if (!$response->successful()) {
                    $errorMessage = $response->json('message') ?? $response->json('error') ?? 'Unknown error occurred';
                    
                    Notification::make()
                        ->title('Failed to send to PostSimple')
                        ->body($errorMessage)
                        ->danger()
                        ->send();
                    
                    return;
                }

                $batchId = $response->json('batch_id');

                if (empty($batchId)) {
                    Notification::make()
                        ->title('Error')
                        ->body('No batch ID received from PostSimple')
                        ->danger()
                        ->send();
                    
                    return;
                }

                // Success - show notification with link
                Notification::make()
                    ->title('Successfully sent to PostSimple!')
                    ->body('Click to view in PostSimple')
                    ->success()
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('view')
                            ->label('Open in PostSimple')
                            ->url($this->postSimpleUrl . '?batch=' . $batchId, shouldOpenInNewTab: true)
                            ->button(),
                    ])
                    ->send();

            } catch (\Exception $e) {
                Notification::make()
                    ->title('Error sending to PostSimple')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        });
    }

    protected function getRecordTitle(Model $record): ?string
    {
        // If custom attribute is set, use that
        if ($this->titleAttribute) {
            if (isset($record->{$this->titleAttribute})) {
                return $record->{$this->titleAttribute};
            }
        }
        
        // Try common title fields
        $titleFields = ['title', 'name', 'heading', 'subject'];
        
        foreach ($titleFields as $field) {
            if (isset($record->{$field}) && !empty($record->{$field})) {
                return $record->{$field};
            }
        }

        // Fallback: try to use model name with ID
        return class_basename($record) . ' #' . $record->getKey();
    }

    protected function getRecordUrl(Model $record): ?string
    {
        // If custom URL closure is provided, use that
        if ($this->urlUsing) {
            return ($this->urlUsing)($record);
        }
        
        // Try to get URL from model
        if (method_exists($record, 'getUrl')) {
            return $record->getUrl();
        }

        if (method_exists($record, 'url')) {
            return $record->url();
        }

        // Check if there's a slug and try to build URL
        if (isset($record->slug)) {
            $tableName = $record->getTable();
            return url('/' . str_replace('_', '-', $tableName) . '/' . $record->slug);
        }

        // Last resort: just use app URL with model info
        return url('/' . str_replace('_', '-', $record->getTable()) . '/' . $record->getKey());
    }
}
