<?php

use Illuminate\Support\Facades\App;

if (!function_exists('localized_route')) {
    function localized_route(string $name, array $parameters = [], ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();
        
        return route($name, array_merge(['locale' => $locale], $parameters));
    }
}

if (!function_exists('current_locale')) {
    function current_locale(): string
    {
        return App::getLocale();
    }
}

if (!function_exists('switch_locale_url')) {
    function switch_locale_url(string $locale): string
    {
        $currentUrl = request()->fullUrl();
        $currentLocale = App::getLocale();
        
        return str_replace('/' . $currentLocale . '/', '/' . $locale . '/', $currentUrl);
    }
}

if (!function_exists('route_localized')) {
    function route_localized(string $name, array $parameters = []): string
    {
        $locale = App::getLocale();
        
        // If in Slovenian, try the .sl route variant first
        if ($locale === 'sl') {
            $localizedName = $name . '.sl';
            if (\Illuminate\Support\Facades\Route::has($localizedName)) {
                return route($localizedName, $parameters);
            }
        }
        
        // Fall back to the default route
        return route($name, $parameters);
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
