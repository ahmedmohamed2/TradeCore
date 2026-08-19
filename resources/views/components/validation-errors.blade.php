@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'alert alert-danger', 'role' => 'alert']) }}>
        <div class="fw-semibold">{{ __('Whoops! Something went wrong.') }}</div>

        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
