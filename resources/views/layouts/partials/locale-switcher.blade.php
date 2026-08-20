@php
    $currentLocale = app()->getLocale();
    $locales = config('locale.names');
    $asNavItem = $asNavItem ?? true;
@endphp

@if ($asNavItem)
<li class="nav-item dropdown">
@else
<div class="dropdown">
@endif
    <a
        class="{{ $asNavItem ? 'nav-link' : 'btn btn-outline-secondary btn-sm dropdown-toggle' }}"
        href="#"
        id="locale-switcher"
        aria-label="{{ __('general.language') }}"
        data-bs-toggle="dropdown"
        aria-expanded="false"
    >
        <i class="bi bi-translate"></i>
        @unless ($asNavItem)
            <span>{{ $locales[$currentLocale] ?? $currentLocale }}</span>
        @endunless
    </a>
    <ul
        class="dropdown-menu dropdown-menu-end"
        aria-labelledby="locale-switcher"
        style="--bs-dropdown-min-width: 8rem"
    >
        @foreach ($locales as $code => $name)
            <li>
                <form method="POST" action="{{ route('locale.update') }}">
                    @csrf
                    <input type="hidden" name="locale" value="{{ $code }}">
                    <button
                        type="submit"
                        class="dropdown-item d-flex align-items-center {{ $currentLocale === $code ? 'active' : '' }}"
                    >
                        {{ $name }}
                        @if ($currentLocale === $code)
                            <i class="bi bi-check-lg ms-auto"></i>
                        @endif
                    </button>
                </form>
            </li>
        @endforeach
    </ul>
@if ($asNavItem)
</li>
@else
</div>
@endif
