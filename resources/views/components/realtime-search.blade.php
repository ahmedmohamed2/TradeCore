@props([
    'action',
    'resultsId',
    'value' => '',
    'placeholder' => '',
    'inputId' => 'search',
])

<form
    method="GET"
    action="{{ $action }}"
    {{ $attributes->merge(['class' => 'access-search']) }}
    role="search"
    data-realtime-search
    data-results-id="{{ $resultsId }}"
    data-searching="{{ __('general.searching') }}"
>
    <label for="{{ $inputId }}" class="visually-hidden">{{ __('general.search') }}</label>
    <input
        id="{{ $inputId }}"
        type="search"
        name="search"
        value="{{ $value }}"
        class="form-control"
        placeholder="{{ $placeholder }}"
        autocomplete="off"
        enterkeyhint="search"
    >
    <button type="submit" class="btn btn-outline-secondary">{{ __('general.search') }}</button>
    <span class="realtime-search-status visually-hidden" data-realtime-search-status aria-live="polite"></span>
</form>

@once
    @push('scripts')
        <script src="{{ asset('assets/js/realtime-search.js') }}"></script>
    @endpush
@endonce
