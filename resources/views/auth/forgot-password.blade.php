<x-guest-layout>
    <x-auth-card :title="__('Forgot your password?')">
        <p class="text-muted mb-3">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </p>

        @session('status')
            <div class="alert alert-success" role="alert">
                {{ $value }}
            </div>
        @endsession

        <x-validation-errors class="mb-3" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="input-group mb-3">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" required autofocus autocomplete="username">
                <div class="input-group-text">
                    <span class="bi bi-envelope"></span>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">{{ __('Email Password Reset Link') }}</button>
            </div>
        </form>

        <p class="mb-0 mt-3">
            <a href="{{ route('login') }}">{{ __('Back to login') }}</a>
        </p>
    </x-auth-card>
</x-guest-layout>
