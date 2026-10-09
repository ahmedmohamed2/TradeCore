<x-guest-layout>
    <x-auth-card :title="__('Two Factor Authentication')">
        <div x-data="{ recovery: false }">
            <p class="text-muted mb-3" x-show="! recovery">
                {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
            </p>

            <p class="text-muted mb-3" x-cloak x-show="recovery">
                {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
            </p>

            <x-validation-errors class="mb-3" />

            <form method="POST" action="{{ route('two-factor.login') }}">
                @csrf

                <div class="input-group mb-3" x-show="! recovery">
                    <input id="code" type="text" class="form-control" inputmode="numeric" name="code" placeholder="{{ __('Code') }}" autofocus x-ref="code" autocomplete="one-time-code">
                    <div class="input-group-text">
                        <span class="bi bi-shield-lock"></span>
                    </div>
                </div>

                <div class="input-group mb-3" x-cloak x-show="recovery">
                    <input id="recovery_code" type="text" class="form-control" name="recovery_code" placeholder="{{ __('Recovery Code') }}" x-ref="recovery_code" autocomplete="one-time-code">
                    <div class="input-group-text">
                        <span class="bi bi-key"></span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button type="button" class="btn btn-link p-0"
                            x-show="! recovery"
                            x-on:click="
                                recovery = true;
                                $nextTick(() => { $refs.recovery_code.focus() })
                            ">
                        {{ __('Use a recovery code') }}
                    </button>

                    <button type="button" class="btn btn-link p-0"
                            x-cloak
                            x-show="recovery"
                            x-on:click="
                                recovery = false;
                                $nextTick(() => { $refs.code.focus() })
                            ">
                        {{ __('Use an authentication code') }}
                    </button>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('Log in') }}</button>
                </div>
            </form>
        </div>
    </x-auth-card>
</x-guest-layout>
