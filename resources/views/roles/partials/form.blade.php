@php
    $editing = $role !== null;
    $selectedPermissions = old('permissions', $editing ? $role->permissions->pluck('name')->all() : []);
    $selectedPermissions = is_array($selectedPermissions) ? $selectedPermissions : [];
@endphp

<form method="POST" action="{{ $editing ? route('roles.update', $role) : route('roles.store') }}">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <section class="settings-card" aria-labelledby="role-name-heading">
        <div class="settings-card-head">
            <span class="settings-icon" aria-hidden="true"><i class="bi bi-shield-lock"></i></span>
            <div>
                <h2 id="role-name-heading" class="settings-card-title">{{ __('roles.name') }}</h2>
                <p class="settings-card-hint">{{ __('roles.name_hint') }}</p>
            </div>
        </div>

        <div class="settings-field mb-0">
            <label for="name" class="settings-label">
                {{ __('roles.name') }}
                <span class="settings-required">{{ __('general.required') }}</span>
            </label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $editing ? $role->name : '') }}"
                class="form-control @error('name') is-invalid @enderror"
                required
                maxlength="255"
            >
            <x-input-error for="name" class="mt-1" />
        </div>
    </section>

    <section class="settings-card" aria-labelledby="role-permissions-heading">
        <div class="settings-card-head">
            <span class="settings-icon" aria-hidden="true"><i class="bi bi-check2-square"></i></span>
            <div>
                <h2 id="role-permissions-heading" class="settings-card-title">{{ __('roles.permissions') }}</h2>
                <p class="settings-card-hint">{{ __('roles.permissions_hint') }}</p>
            </div>
        </div>

        @foreach ($permissionGroups as $group => $permissions)
            <fieldset class="mb-4" data-permission-group>
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                    <legend class="settings-card-title float-none w-auto mb-0 fs-6">{{ __('permissions.groups.'.$group) }}</legend>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-select-all>{{ __('roles.select_all') }}</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-clear-all>{{ __('roles.clear_all') }}</button>
                    </div>
                </div>

                <div class="permission-grid">
                    @foreach ($permissions as $permission)
                        @php($permissionId = 'permission-'.str_replace('.', '-', $permission->value))
                        <div class="form-check">
                            <input
                                id="{{ $permissionId }}"
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permission->value }}"
                                class="form-check-input"
                                @checked(in_array($permission->value, $selectedPermissions, true))
                            >
                            <label class="form-check-label" for="{{ $permissionId }}">
                                {{ __('permissions.names.'.$permission->value) }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </fieldset>
        @endforeach

        <x-input-error for="permissions" class="mt-1" />
        @foreach ($errors->get('permissions.*') as $message)
            <p class="text-danger small mb-0">{{ $message }}</p>
        @endforeach
    </section>

    <div class="settings-actions">
        <button type="submit" class="btn btn-primary">
            {{ $editing ? __('general.save_changes') : __('roles.create') }}
        </button>
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">{{ __('general.cancel') }}</a>
    </div>
</form>

<script>
    document.querySelectorAll('[data-permission-group]').forEach((group) => {
        const boxes = () => group.querySelectorAll('input[type="checkbox"]');

        group.querySelector('[data-select-all]')?.addEventListener('click', () => {
            boxes().forEach((box) => {
                box.checked = true;
            });
        });

        group.querySelector('[data-clear-all]')?.addEventListener('click', () => {
            boxes().forEach((box) => {
                box.checked = false;
            });
        });
    });
</script>
