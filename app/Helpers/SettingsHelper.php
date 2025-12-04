<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsHelper
{
    /**
     * Get a setting value by its code
     *
     * @param string $code
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $code, $default = null)
    {
        return Cache::rememberForever("setting_{$code}", function () use ($code, $default) {
            $setting = Setting::where('code', $code)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value
     *
     * @param string $code
     * @param string $value
     * @return void
     */
    public static function set(string $code, string $value): void
    {
        Setting::updateOrCreate(
            ['code' => $code],
            ['value' => $value]
        );

        // Clear the cache for this setting
        Cache::forget("setting_{$code}");
    }

    /**
     * Get all settings as key-value pairs
     *
     * @return array
     */
    public static function all(): array
    {
        return Cache::rememberForever('all_settings', function () {
            return Setting::pluck('value', 'code')->toArray();
        });
    }

    /**
     * Clear all settings cache
     *
     * @return void
     */
    public static function clearCache(): void
    {
        $settings = Setting::pluck('code');
        
        foreach ($settings as $code) {
            Cache::forget("setting_{$code}");
        }
        
        Cache::forget('all_settings');
    }
}
