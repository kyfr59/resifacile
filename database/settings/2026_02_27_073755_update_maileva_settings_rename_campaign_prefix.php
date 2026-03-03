<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if ($this->migrator->exists('maileva.campaign_number')) {
            $this->migrator->rename(
                'maileva.campaign_prefix',
                'maileva.sending_prefix'
            );
        }
    }
};