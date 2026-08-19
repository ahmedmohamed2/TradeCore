@props(['title' => null])

<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}"><b>Trade</b>Core</a>
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            @if ($title)
                <p class="login-box-msg">{{ $title }}</p>
            @endif

            {{ $slot }}
        </div>
    </div>
</div>
