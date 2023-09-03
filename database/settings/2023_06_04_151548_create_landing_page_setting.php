<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {  
        $this->migrator->add('landing_page.title');
        $this->migrator->add('landing_page.logo');
        $this->migrator->add('landing_page.logo_dark');
        $this->migrator->add('landing_page.description');
        $this->migrator->add('landing_page.email');
        $this->migrator->add('landing_page.facebook');
        $this->migrator->add('landing_page.instagram');
        $this->migrator->add('landing_page.twitter');
        $this->migrator->add('landing_page.phone');
        $this->migrator->add('landing_page.location');
        $this->migrator->add('landing_page.footer', config('app.name'));
    }
};
