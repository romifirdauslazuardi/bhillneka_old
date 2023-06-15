<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {  
        $this->migrator->add('dashboard.title');
        $this->migrator->add('dashboard.logo');
        $this->migrator->add('dashboard.logo_icon');
        $this->migrator->add('dashboard.footer', config('app.name'));
    }
};
