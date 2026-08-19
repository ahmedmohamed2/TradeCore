<x-guest-layout>
    <x-auth-card :title="__('Register a new membership')">
        <x-validation-errors class="mb-3" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="input-group mb-3">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="{{ __('Name') }}" required autofocus autocomplete="name">
                <div class="input-group-text">
                    <span class="bi bi-person"></span>
                </div>
            </div>

            <div class="input-group mb-3">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" required autocomplete="username">
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

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                    <label class="form-check-label" for="terms">
                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'">'.__('Terms of Service').'</a>',
                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'">'.__('Privacy Policy').'</a>',
                        ]) !!}
                    </label>
                </div>
            @endif

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
            </div>
        </form>

        <p class="mb-0 mt-3">
            <a href="{{ route('login') }}">{{ __('Already registered?') }}</a>
        </p>
    </x-auth-card>
</x-guest-layout>
