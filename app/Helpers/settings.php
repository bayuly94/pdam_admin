<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    /**
     * Get or set a setting value
     *
     * @param string $code
     * @param mixed $default
     * @return mixed
     */
    function setting($code, $default = null)
    {
        return Cache::rememberForever("setting_{$code}", function () use ($code, $default) {
            $setting = Setting::where('code', $code)->first();
            return $setting ? $setting->value : $default;
        });
    }
}

if (!function_exists('set_setting')) {
    /**
     * Set a setting value
     *
     * @param string $code
     * @param string $value
     * @return void
     */
    function set_setting($code, $value)
    {
        Setting::updateOrCreate(
            ['code' => $code],
            ['value' => $value]
        );

        // Clear the cache for this setting
        Cache::forget("setting_{$code}");
    }
}

if (!function_exists('forget_setting')) {
    /**
     * Remove a setting from cache
     *
     * @param string $code
     * @return void
     */
    function forget_setting($code)
    {
        Cache::forget("setting_{$code}");
    }
}
