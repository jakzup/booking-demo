@php
    $currentRouteName = request()->route()->getName();
    $currentParams = request()->route()->parameters();
    
    // Try to generate URLs for both locales using current route
    try {
        // For English
        if ($currentRouteName && str_ends_with($currentRouteName, '.sl')) {
            $enRouteName = str_replace('.sl', '', $currentRouteName);
        } else {
            $enRouteName = $currentRouteName;
        }
        $enUrl = $enRouteName ? route($enRouteName, array_merge($currentParams, ['locale' => 'en'])) : '/en';
        
        // For Slovenian
        $slRouteName = $currentRouteName && !str_ends_with($currentRouteName, '.sl') ? $currentRouteName . '.sl' : $currentRouteName;
        $slUrl = \Illuminate\Support\Facades\Route::has($slRouteName) 
            ? route($slRouteName, array_merge($currentParams, ['locale' => 'sl'])) 
            : '/sl';
    } catch (\Exception $e) {
        // Fallback to simple path replacement if route generation fails
        $currentPath = request()->path();
        if (preg_match('/^(en|sl)(\/.*)?$/', $currentPath, $matches)) {
            $afterLocale = $matches[2] ?? '';
            $enUrl = '/en' . $afterLocale;
            $slUrl = '/sl' . $afterLocale;
        } else {
            $enUrl = '/en';
            $slUrl = '/sl';
        }
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
