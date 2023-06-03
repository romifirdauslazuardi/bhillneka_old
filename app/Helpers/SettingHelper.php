<?php

namespace App\Helpers;

// Function settings()
use App\Settings\DashboardSetting;

class SettingHelper
{
    public static function settings(string $group, string $key)
    {
        return match ($group) {
            'dashboard' => app(DashboardSetting::class)->$key,
            default => null,
        };
    }
}
