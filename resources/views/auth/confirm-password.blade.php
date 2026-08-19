<x-guest-layout>
    <x-auth-card :title="__('Confirm Password')">
        <p class="text-muted mb-3">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </p>

        <x-validation-errors class="mb-3" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="input-group mb-3">
                <input id="password" type="password" class="form-control" name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password" autofocus>
                <div class="input-group-text">
                    <span class="bi bi-lock-fill"></span>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">{{ __('Confirm') }}</button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
