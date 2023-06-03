<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DashboardSetting extends Settings
{
    public ?string $logo;

    public ?string $logo_dark;

    public ?string $logo_icon;

    public ?string $title;

    public ?string $footer;

    public static function group(): string
    {
        return 'dashboard';
    }
}
