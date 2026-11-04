<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported application locales.
     *
     * @var array<string>
     */
    protected array $supportedLocales = ['id', 'en'];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = null;

        // 1. Check session (persisted by web switcher)
        if ($request->hasSession() && $request->session()->has('locale')) {
            $locale = $request->session()->get('locale');
        }

        // 2. Check query parameter (?lang=en or ?locale=id)
        if ($request->filled('lang') && in_array($request->query('lang'), $this->supportedLocales)) {
            $locale = $request->query('lang');
            if ($request->hasSession()) {
                $request->session()->put('locale', $locale);
            }
        } elseif ($request->filled('locale') && in_array($request->query('locale'), $this->supportedLocales)) {
            $locale = $request->query('locale');
            if ($request->hasSession()) {
                $request->session()->put('locale', $locale);
            }
        }

        // 3. Check Accept-Language header for API requests
        if (! $locale && $request->hasHeader('Accept-Language')) {
            $preferred = substr($request->header('Accept-Language'), 0, 2);
            if (in_array($preferred, $this->supportedLocales)) {
                $locale = $preferred;
            }
        }

        // 4. Default fallback
        if (! $locale || ! in_array($locale, $this->supportedLocales)) {
            $locale = config('app.locale', 'id');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
