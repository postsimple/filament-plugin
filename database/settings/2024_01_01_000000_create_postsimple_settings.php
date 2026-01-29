<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('postsimple.api_key', '');
    }

    public function down(): void
    {
        $this->migrator->delete('postsimple.api_key');
    }
};
