<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

final class RealtimeSearch
{
    public const Header = 'X-Realtime-Search';

    public static function requested(Request $request): bool
    {
        return $request->header(self::Header) === '1';
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function view(Request $request, string $view, array $data, string $fragment = 'results'): View|Response
    {
        $page = view($view, $data);

        if (! self::requested($request)) {
            return $page;
        }

        return response($page->fragment($fragment))
            ->header(self::Header, '1');
    }
}
