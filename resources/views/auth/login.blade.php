<x-guest-layout>
    <x-auth-card :title="__('Sign in to start your session')">
        <x-validation-errors class="mb-3" />

        @session('status')
            <div class="alert alert-success" role="alert">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="input-group mb-3">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" required autofocus autocomplete="username">
                <div class="input-group-text">
                    <span class="bi bi-envelope"></span>
                </div>
            </div>

            <div class="input-group mb-3">
                <input id="password" type="password" class="form-control" name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">
                <div class="input-group-text">
                    <span class="bi bi-lock-fill"></span>
                </div>
            </div>

            <div class="row">
                <div class="col-8">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                        <label class="form-check-label" for="remember_me">
                            {{ __('Remember me') }}
                        </label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">{{ __('Log in') }}</button>
                    </div>
                </div>
            </div>
        </form>

        @if (Route::has('password.request'))
            <p class="mb-1 mt-3">
                <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
            </p>
        @endif

        @if (Route::has('register'))
            <p class="mb-0">
                <a href="{{ route('register') }}">{{ __('Register a new membership') }}</a>
            </p>
        @endif
    </x-auth-card>
</x-guest-layout>
