@php
    $editing = $treasury !== null;
@endphp

<form method="POST" action="{{ $editing ? route('treasuries.update', $treasury) : route('treasuries.store') }}">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <section class="settings-card" aria-labelledby="treasury-details">
        <div class="settings-card-head">
            <span class="settings-icon" aria-hidden="true"><i class="bi bi-safe"></i></span>
            <div>
                <h2 id="treasury-details" class="settings-card-title">{{ __('treasuries.section_details') }}</h2>
                <p class="settings-card-hint">{{ __('treasuries.section_details_hint') }}</p>
            </div>
        </div>

        <div class="settings-field">
            <label for="code" class="settings-label">
                {{ __('treasuries.code') }}
                <span class="settings-required">{{ __('general.required') }}</span>
            </label>
            <input
                id="code"
                name="code"
                type="text"
                value="{{ old('code', $editing ? $treasury->code : '') }}"
                class="form-control ltr-nums @error('code') is-invalid @enderror"
                dir="ltr"
                required
                maxlength="32"
                autocomplete="off"
                aria-describedby="code_hint"
            >
            <p id="code_hint" class="settings-hint">{{ __('treasuries.code_hint') }}</p>
            <x-input-error for="code" class="mt-1" />
        </div>

        <div class="settings-field">
            <label for="name" class="settings-label">
                {{ __('treasuries.name') }}
                <span class="settings-required">{{ __('general.required') }}</span>
            </label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $editing ? $treasury->name : '') }}"
                class="form-control @error('name') is-invalid @enderror"
                required
                maxlength="255"
            >
            <x-input-error for="name" class="mt-1" />
        </div>

        <div class="settings-field">
            <label for="opening_balance" class="settings-label">
                {{ __('treasuries.opening_balance') }}
                <span class="settings-required">{{ __('general.required') }}</span>
            </label>
            <input
                id="opening_balance"
                name="opening_balance"
                type="number"
                inputmode="decimal"
                min="0"
                step="0.01"
                value="{{ old('opening_balance', $editing ? $treasury->opening_balance : '0.00') }}"
                class="form-control ltr-nums @error('opening_balance') is-invalid @enderror"
                dir="ltr"
                required
                aria-describedby="opening_balance_hint"
            >
            <p id="opening_balance_hint" class="settings-hint">{{ __('treasuries.opening_balance_hint') }}</p>
            <x-input-error for="opening_balance" class="mt-1" />
        </div>

        <div class="settings-field mb-0">
            <label for="notes" class="settings-label">{{ __('treasuries.notes') }}</label>
            <textarea
                id="notes"
                name="notes"
                rows="3"
                maxlength="2000"
                class="form-control @error('notes') is-invalid @enderror"
            >{{ old('notes', $editing ? $treasury->notes : '') }}</textarea>
            <x-input-error for="notes" class="mt-1" />
        </div>
    </section>

    <section class="settings-card" aria-labelledby="treasury-status">
        <div class="settings-card-head mb-2">
            <span class="settings-icon" aria-hidden="true"><i class="bi bi-toggles"></i></span>
            <div>
                <h2 id="treasury-status" class="settings-card-title">{{ __('treasuries.section_status') }}</h2>
                <p class="settings-card-hint">{{ __('treasuries.section_status_hint') }}</p>
            </div>
        </div>

        <div class="form-check form-switch settings-switch">
            <div>
                <label for="is_master" class="settings-label mb-0">{{ __('treasuries.master') }}</label>
                <p id="is_master_hint" class="settings-hint">{{ __('treasuries.master_hint') }}</p>
            </div>
            <input
                id="is_master"
                type="checkbox"
                name="is_master"
                value="1"
                role="switch"
                class="form-check-input @error('is_master') is-invalid @enderror"
                aria-describedby="is_master_hint"
                @checked(old('is_master', $editing ? $treasury->is_master : false))
            >
        </div>
        <x-input-error for="is_master" class="mt-1" />

        <div class="form-check form-switch settings-switch mt-3">
            <div>
                <label for="active" class="settings-label mb-0">{{ __('general.active') }}</label>
                <p id="active_hint" class="settings-hint">{{ __('treasuries.active_hint') }}</p>
            </div>
            <input
                id="active"
                type="checkbox"
                name="active"
                value="1"
                role="switch"
                class="form-check-input @error('active') is-invalid @enderror"
                aria-describedby="active_hint"
                @checked(old('active', $editing ? $treasury->active : true))
            >
        </div>
        <x-input-error for="active" class="mt-1" />
    </section>

    @if ($editing)
        <section class="settings-card" aria-labelledby="treasury-counters">
            <div class="settings-card-head mb-2">
                <span class="settings-icon" aria-hidden="true"><i class="bi bi-receipt"></i></span>
                <div>
                    <h2 id="treasury-counters" class="settings-card-title">{{ __('treasuries.section_counters') }}</h2>
                    <p class="settings-card-hint">{{ __('treasuries.counters_hint') }}</p>
                </div>
            </div>

            <dl class="row mb-0">
                <dt class="col-sm-4">{{ __('treasuries.last_collection_number') }}</dt>
                <dd class="col-sm-8 ltr-nums" dir="ltr">{{ $treasury->last_collection_number }}</dd>
                <dt class="col-sm-4">{{ __('treasuries.last_payment_number') }}</dt>
                <dd class="col-sm-8 ltr-nums mb-0" dir="ltr">{{ $treasury->last_payment_number }}</dd>
            </dl>
        </section>
    @endif

    <div class="settings-actions">
        <button type="submit" class="btn btn-primary">
            {{ $editing ? __('general.save_changes') : __('treasuries.create') }}
        </button>
        <a href="{{ route('treasuries.index') }}" class="btn btn-outline-secondary">{{ __('general.cancel') }}</a>
    </div>
</form>
