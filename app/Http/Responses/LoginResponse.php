<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LoginResponse implements LoginResponseContract
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

        // Get current locale and redirect to localized home page
        $locale = app()->getLocale();
        return redirect(LaravelLocalization::getLocalizedURL($locale, '/'));
    }
}
