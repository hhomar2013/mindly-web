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
        if (! $request->is('api/*')) {
            return $next($request);
        }
        $headerLocale       = $request->header('Accept-language');
        $locale             = substr($headerLocale, 0, 2);
        $supportedLanguages = ['ar', 'en'];

        if ($locale && in_array($locale, $supportedLanguages)) {
            app()->setLocale($locale);
        }
        return $next($request);
    }
}
