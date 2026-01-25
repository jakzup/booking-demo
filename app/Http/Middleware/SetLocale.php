<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->getLocale($request);

        if (in_array($locale, supported_locales())) {
            App::setLocale($locale);
            Session::put('locale', $locale);
            
            // Set default locale for URL generation
            URL::defaults(['locale' => $locale]);
        }

        return $next($request);
    }

    protected function getLocale(Request $request): string
    {
        // 1. Check URL segment (primary source)
        $segment = $request->segment(1);
        if (in_array($segment, supported_locales())) {
            return $segment;
        }

        // 2. Check session
        if (Session::has('locale')) {
            return Session::get('locale');
        }

        // 3. Check browser preference
        $browserLocale = substr($request->getPreferredLanguage(supported_locales()), 0, 2);
        if (in_array($browserLocale, supported_locales())) {
            return $browserLocale;
        }

        // 4. Default fallback
        return default_locale();
    }
}
