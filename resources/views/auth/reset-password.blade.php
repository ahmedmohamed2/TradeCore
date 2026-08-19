<x-guest-layout>
    <x-auth-card :title="__('Reset Password')">
        <x-validation-errors class="mb-3" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="input-group mb-3">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email', $request->email) }}" placeholder="{{ __('Email') }}" required autofocus autocomplete="username">
                <div class="input-group-text">
                    <span class="bi bi-envelope"></span>
                </div>
            </div>

            <div class="input-group mb-3">
                <input id="password" type="password" class="form-control" name="password" placeholder="{{ __('Password') }}" required autocomplete="new-password">
                <div class="input-group-text">
                    <span class="bi bi-lock-fill"></span>
                </div>
            </div>

            <div class="input-group mb-3">
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required autocomplete="new-password">
                <div class="input-group-text">
                    <span class="bi bi-lock-fill"></span>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">{{ __('Reset Password') }}</button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
