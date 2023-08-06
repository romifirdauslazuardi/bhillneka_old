<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DashboardSetting extends Settings
{
    public ?string $logo;

    public ?string $logo_icon;

    public ?string $favicon;

    public ?string $title;

    public ?string $description;

    public ?string $keyword;

    public ?string $footer;

    public static function group(): string
    {
        return 'dashboard';
    }
}
