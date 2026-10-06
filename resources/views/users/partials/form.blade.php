@php
    $editing = $user !== null;
    $selectedRoles = old('roles', $editing ? $user->roles->pluck('name')->all() : []);
    $selectedRoles = is_array($selectedRoles) ? $selectedRoles : [];
    $selectedLocale = old('locale', $editing ? $user->locale : config('locale.default'));
@endphp

<form method="POST" action="{{ $editing ? route('users.update', $user) : route('users.store') }}">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <section class="settings-card" aria-labelledby="user-account">
        <div class="settings-card-head">
            <span class="settings-icon" aria-hidden="true"><i class="bi bi-person"></i></span>
            <div>
                <h2 id="user-account" class="settings-card-title">{{ __('users.section_account') }}</h2>
                <p class="settings-card-hint">{{ __('users.section_account_hint') }}</p>
            </div>
        </div>

        <div class="settings-field">
            <label for="name" class="settings-label">
                {{ __('users.name') }}
                <span class="settings-required">{{ __('general.required') }}</span>
            </label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $editing ? $user->name : '') }}"
                class="form-control @error('name') is-invalid @enderror"
                required
                maxlength="255"
                autocomplete="name"
            >
            <x-input-error for="name" class="mt-1" />
        </div>

        <div class="settings-field">
            <label for="email" class="settings-label">
                {{ __('users.email') }}
                <span class="settings-required">{{ __('general.required') }}</span>
            </label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $editing ? $user->email : '') }}"
                class="form-control ltr-nums @error('email') is-invalid @enderror"
                dir="ltr"
                required
                maxlength="255"
                autocomplete="email"
            >
            <x-input-error for="email" class="mt-1" />
        </div>

        <div class="settings-field mb-0">
            <label for="locale" class="settings-label">
                {{ __('users.locale') }}
                <span class="settings-required">{{ __('general.required') }}</span>
            </label>
            <select id="locale" name="locale" class="form-select @error('locale') is-invalid @enderror" required>
                @foreach (config('locale.names') as $code => $label)
                    <option value="{{ $code }}" @selected($selectedLocale === $code)>{{ $label }}</option>
                @endforeach
            </select>
            <x-input-error for="locale" class="mt-1" />
        </div>
    </section>

    <section class="settings-card" aria-labelledby="user-security">
        <div class="settings-card-head">
            <span class="settings-icon" aria-hidden="true"><i class="bi bi-key"></i></span>
            <div>
                <h2 id="user-security" class="settings-card-title">{{ __('users.section_security') }}</h2>
                <p class="settings-card-hint">{{ $editing ? __('users.password_hint') : __('users.section_security_hint') }}</p>
            </div>
        </div>

        <div class="settings-field">
            <label for="password" class="settings-label">
                {{ __('users.password') }}
                @unless ($editing)
                    <span class="settings-required">{{ __('general.required') }}</span>
                @endunless
            </label>
            <input
                id="password"
                name="password"
                type="password"
                class="form-control @error('password') is-invalid @enderror"
                @required(! $editing)
                autocomplete="new-password"
            >
            <x-input-error for="password" class="mt-1" />
        </div>

        <div class="settings-field mb-0">
            <label for="password_confirmation" class="settings-label">{{ __('users.password_confirmation') }}</label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                class="form-control"
                @required(! $editing)
                autocomplete="new-password"
            >
        </div>
    </section>

    <section class="settings-card" aria-labelledby="user-roles">
        <div class="settings-card-head">
            <span class="settings-icon" aria-hidden="true"><i class="bi bi-shield-lock"></i></span>
            <div>
                <h2 id="user-roles" class="settings-card-title">{{ __('users.section_roles') }}</h2>
                <p class="settings-card-hint">{{ __('users.section_roles_hint') }}</p>
            </div>
        </div>

        @if ($roles->isEmpty())
            <p class="settings-empty-note">{{ __('users.no_roles') }}</p>
        @else
            <div class="permission-grid">
                @foreach ($roles as $role)
                    <label class="role-choice" for="role-{{ $role->id }}">
                        <input
                            id="role-{{ $role->id }}"
                            type="checkbox"
                            name="roles[]"
                            value="{{ $role->name }}"
                            class="form-check-input"
                            @checked(in_array($role->name, $selectedRoles, true))
                        >
                        <span>{{ \App\Support\RoleName::label($role->name) }}</span>
                    </label>
                @endforeach
            </div>
        @endif

        <x-input-error for="roles" class="mt-2" />
        @foreach ($errors->get('roles.*') as $message)
            <p class="text-danger small mb-0 mt-1">{{ $message }}</p>
        @endforeach
    </section>

    <div class="settings-actions">
        <button type="submit" class="btn btn-primary">
            {{ $editing ? __('general.save_changes') : __('users.create') }}
        </button>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">{{ __('general.cancel') }}</a>
    </div>
</form>
