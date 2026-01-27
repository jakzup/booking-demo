@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    
    // Get current route info
    $route = request()->route();
    $routeName = $route ? $route->getName() : null;
    $params = $route ? $route->parameters() : [];
    
    // Extract route keys from model instances
    $cleanParams = [];
    foreach ($params as $key => $value) {
        if (is_object($value) && method_exists($value, 'getRouteKey')) {
            $cleanParams[$key] = $value->getRouteKey();
        } else {
            $cleanParams[$key] = $value;
        }
    }
    
    // Map route names to translation keys
    $routeToTransKey = [
        'login' => 'login',
        'register' => 'register',
        'rooms.show' => 'rooms',
        'reservations.index' => 'my-reservations',
        'profile.edit' => 'settings.profile',
        'user-password.edit' => 'settings.password',
        'appearance.edit' => 'settings.appearance',
        'two-factor.show' => 'settings.two-factor',
    ];
    
    if ($routeName && isset($routeToTransKey[$routeName])) {
        $transKey = 'routes.' . $routeToTransKey[$routeName];
        $currentLocale = app()->getLocale();
        
        // Generate English URL
        app()->setLocale('en');
        $enPath = trans($transKey);
        foreach ($cleanParams as $key => $value) {
            $enPath = str_replace('{' . $key . '}', $value, $enPath);
        }
        $enUrl = url('/en/' . $enPath);
        
        // Generate Slovenian URL
        app()->setLocale('sl');
        $slPath = trans($transKey);
        foreach ($cleanParams as $key => $value) {
            $slPath = str_replace('{' . $key . '}', $value, $slPath);
        }
        $slUrl = url('/sl/' . $slPath);
        
        // Restore locale
        app()->setLocale($currentLocale);
    } else {
        // Fallback for routes without translation (like home)
        $enUrl = LaravelLocalization::getLocalizedURL('en');
        $slUrl = LaravelLocalization::getLocalizedURL('sl');
    }
@endphp

<flux:dropdown position="bottom" align="start">
    <flux:button variant="ghost" size="sm" icon-trailing="chevron-down">
        <span class="flex items-center gap-2">
            <flux:icon.language variant="micro" />
            {{ app()->getLocale() === 'en' ? 'EN' : 'SL' }}
        </span>
    </flux:button>

    <flux:menu class="min-w-32">
        <flux:menu.item href="{{ $enUrl }}" wire:navigate>
            English
        </flux:menu.item>
        <flux:menu.item href="{{ $slUrl }}" wire:navigate>
            Slovenščina
        </flux:menu.item>
    </flux:menu>
</flux:dropdown>
