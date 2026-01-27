<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        if ($request->expectsJson()) {
            return new JsonResponse('', 204);
        }

        // Get current locale and redirect to localized login page
        $locale = app()->getLocale();
        return redirect(LaravelLocalization::getLocalizedURL($locale, route('login')));
    }
}
