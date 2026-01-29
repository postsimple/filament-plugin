<?php

namespace PostSimple\FilamentPostSimple\Actions;

use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

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

    public function titleAttribute(string $attribute): static
    {
        $this->titleAttribute = $attribute;

        return $this;
    }

    public function urlUsing(\Closure $callback): static
    {
        $this->urlUsing = $callback;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-postsimple::messages.action.label'));

        $this->icon('heroicon-o-share');

        $this->color('primary');

        $this->requiresConfirmation();

        $this->modalHeading(__('filament-postsimple::messages.action.modal_heading'));

        $this->modalDescription(__('filament-postsimple::messages.action.modal_description'));

        $this->modalSubmitActionLabel(__('filament-postsimple::messages.action.modal_submit'));

        $this->action(function (Model $record) {
            try {
                $apiKey = config('filament-postsimple.api_key');

                if (empty($apiKey)) {
                    Notification::make()
                        ->title(__('filament-postsimple::messages.notifications.api_key_missing.title'))
                        ->body(__('filament-postsimple::messages.notifications.api_key_missing.body'))
                        ->danger()
                        ->send();

                    return;
                }

                $title = $this->resolveRecordTitle($record);

                if (empty($title)) {
                    Notification::make()
                        ->title(__('filament-postsimple::messages.notifications.no_title.title'))
                        ->body(__('filament-postsimple::messages.notifications.no_title.body'))
                        ->danger()
                        ->send();

                    return;
                }

                $url = $this->resolveRecordUrl($record);

                if (empty($url)) {
                    Notification::make()
                        ->title(__('filament-postsimple::messages.notifications.no_url.title'))
                        ->body(__('filament-postsimple::messages.notifications.no_url.body'))
                        ->danger()
                        ->send();

                    return;
                }

                $response = Http::withHeaders([
                    'X-API-PostSimple-Key' => $apiKey,
                ])
                    ->timeout(30)
                    ->post($this->apiEndpoint, [
                        'title' => $title,
                        'url' => $url,
                    ]);

                if (! $response->successful()) {
                    $errorMessage = $response->json('message') ?? $response->json('error') ?? 'Unknown error occurred';

                    Notification::make()
                        ->title(__('filament-postsimple::messages.notifications.api_error.title'))
                        ->body($errorMessage)
                        ->danger()
                        ->send();

                    return;
                }

                $batchId = $response->json('batch_id');

                if (empty($batchId)) {
                    Notification::make()
                        ->title(__('filament-postsimple::messages.notifications.no_batch_id.title'))
                        ->body(__('filament-postsimple::messages.notifications.no_batch_id.body'))
                        ->danger()
                        ->send();

                    return;
                }

                Notification::make()
                    ->title(__('filament-postsimple::messages.notifications.success.title'))
                    ->body(__('filament-postsimple::messages.notifications.success.body'))
                    ->success()
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('view')
                            ->label(__('filament-postsimple::messages.notifications.success.open_button'))
                            ->url($this->postSimpleUrl . '?batch=' . $batchId)
                            ->openUrlInNewTab()
                            ->button(),
                    ])
                    ->send();

            } catch (\Exception $e) {
                Notification::make()
                    ->title(__('filament-postsimple::messages.notifications.error.title'))
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        });
    }

    protected function resolveRecordTitle(Model $record): ?string
    {
        if ($this->titleAttribute) {
            if (isset($record->{$this->titleAttribute})) {
                return $record->{$this->titleAttribute};
            }
        }

        $titleFields = ['title', 'name', 'heading', 'subject'];

        foreach ($titleFields as $field) {
            if (isset($record->{$field}) && ! empty($record->{$field})) {
                return $record->{$field};
            }
        }

        return class_basename($record) . ' #' . $record->getKey();
    }

    protected function resolveRecordUrl(Model $record): ?string
    {
        if ($this->urlUsing) {
            return ($this->urlUsing)($record);
        }

        if (method_exists($record, 'getUrl')) {
            return $record->getUrl();
        }

        if (method_exists($record, 'url')) {
            return $record->url();
        }

        if (isset($record->slug)) {
            $tableName = $record->getTable();

            return url('/' . str_replace('_', '-', $tableName) . '/' . $record->slug);
        }

        return url('/' . str_replace('_', '-', $record->getTable()) . '/' . $record->getKey());
    }
}
