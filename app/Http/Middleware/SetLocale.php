<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);

        App::setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }

    private function resolveLocale(Request $request): string
    {
        /** @var list<string> $available */
        $available = config('locale.available');
        $default = (string) config('locale.default');

        $candidate = $request->user()?->locale
            ?? $request->session()->get('locale')
            ?? $default;

        if (! is_string($candidate) || ! in_array($candidate, $available, true)) {
            return $default;
        }

        return $candidate;
    }
}
