<x-guest-layout>
    <x-auth-card :title="__('Verify Email')">
        <p class="text-muted mb-3">
            {{ __('Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success" role="alert">
                {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-primary">
                    {{ __('Resend Verification Email') }}
                </button>
            </div>
        </form>

        <p class="mb-1">
            <a href="{{ route('profile.show') }}">{{ __('Edit Profile') }}</a>
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link p-0">{{ __('Log Out') }}</button>
        </form>
    </x-auth-card>
</x-guest-layout>
