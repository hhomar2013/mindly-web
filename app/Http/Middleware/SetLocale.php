<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // هنشوف الـ Header اللي جاي، ولو مش موجود هنخلي اللغة الافتراضية 'en'
        $language = $request->header('Accept-Language');

        $locale = substr($language, 0, 2);

        $supportedLanguages = ['ar', 'en'];

        if (in_array($locale, $supportedLanguages)) {
            app()->setLocale($locale);
        } else {
            app()->setLocale(config('app.fallback_locale'));
        }

        return $next($request);
    }
}
