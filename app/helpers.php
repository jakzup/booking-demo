<?php

use Illuminate\Support\Facades\App;

if (!function_exists('current_locale')) {
    function current_locale(): string
    {
        return App::getLocale();
    }
}

if (!function_exists('supported_locales')) {
    function supported_locales(): array
    {
        return config('locales.supported', ['sl']);
    }
}

if (!function_exists('default_locale')) {
    function default_locale(): string
    {
        return config('locales.default', 'sl');
    }
}

if (!function_exists('locale_pattern')) {
    function locale_pattern(): string
    {
        return implode('|', supported_locales());
    }
}
