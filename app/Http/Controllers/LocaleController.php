<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class LocaleController extends Controller
{
    /**
     * Persist the selected locale for the current guest or authenticated user.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var array{locale: string} $validated */
        $validated = $request->validate([
            'locale' => ['required', 'string', Rule::in(config('locale.available'))],
        ]);

        $locale = $validated['locale'];

        $request->session()->put('locale', $locale);

        $request->user()?->update([
            'locale' => $locale,
        ]);

        App::setLocale($locale);

        return back();
    }
}
