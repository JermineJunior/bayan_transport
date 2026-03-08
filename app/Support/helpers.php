<?php

declare(strict_types=1);

/* if (! function_exists('general_setting')) {
    function general_setting(?string $key = null): mixed
    {
        $settings = Cache::rememberForever('general_settings', fn () => GeneralSetting::query()->first());

        if (! $settings) {
            return null;
        }

        if ($key === null) {
            return $settings;
        }

        return $settings->$key ?? null;
    }
}  */
