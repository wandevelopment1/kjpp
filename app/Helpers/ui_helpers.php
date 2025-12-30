<?php

if (!function_exists('ui_value')) {
    function ui_value(string $slug, string $key, $default = null): ?string
    {
        return app(\App\Services\UiConfigService::class)
            ->getValueByGroupSlugAndKey($slug, $key) ?? $default;
    }
}

