@extends('layouts.master')

@section('title', __('system-settings.edit_title'))

@section('css')
    @include('system-settings.partials.styles')
@endsection

@section('title_page_1', __('system-settings.title'))
@section('title_page_2', __('general.edit'))
@section('main_title', __('system-settings.edit_title'))

@section('content')
    <div class="settings-shell">
        <a href="{{ route('system-settings.index') }}" class="settings-back">
            <i class="bi bi-arrow-left" aria-hidden="true"></i>
            {{ __('system-settings.back') }}
        </a>

        <p class="settings-lead">{{ __('system-settings.edit_intro') }}</p>

        <x-validation-errors class="mb-3" />

        <form method="POST" action="{{ route('system-settings.update', $systemSetting) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <section class="settings-card" aria-labelledby="edit-company">
                <div class="settings-card-head">
                    <span class="settings-icon" aria-hidden="true"><i class="bi bi-building"></i></span>
                    <div>
                        <h2 id="edit-company" class="settings-card-title">{{ __('system-settings.section_identity') }}</h2>
                        <p class="settings-card-hint">{{ __('system-settings.section_identity_hint') }}</p>
                    </div>
                </div>

                <div class="settings-photo-row mb-3">
                    @if ($systemSetting->system_photo)
                        <img
                            id="settings-photo-preview"
                            src="{{ asset('uploads/company_photos/' . $systemSetting->system_photo) }}"
                            alt="{{ $systemSetting->system_name }}"
                            class="settings-photo"
                        >
                        <div id="settings-photo-placeholder" class="settings-photo-placeholder d-none" aria-hidden="true">
                            <i class="bi bi-image"></i>
                        </div>
                    @else
                        <img id="settings-photo-preview" alt="" class="settings-photo d-none">
                        <div id="settings-photo-placeholder" class="settings-photo-placeholder" aria-hidden="true">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif

                    <div>
                        <label for="system_photo" class="btn btn-outline-secondary mb-2">
                            {{ __('system-settings.change_photo') }}
                        </label>
                        <input
                            id="system_photo"
                            type="file"
                            name="system_photo"
                            class="settings-file @error('system_photo') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/webp"
                            aria-describedby="system_photo_hint"
                        >
                        <p id="system_photo_hint" class="settings-hint">{{ __('system-settings.system_photo_hint') }}</p>
                        <x-input-error for="system_photo" class="mt-1" />
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="system_name" class="settings-label">
                            {{ __('system-settings.system_name') }}
                            <span class="settings-required">{{ __('system-settings.required') }}</span>
                        </label>
                        <input
                            id="system_name"
                            type="text"
                            name="system_name"
                            class="form-control @error('system_name') is-invalid @enderror"
                            value="{{ old('system_name', $systemSetting->system_name) }}"
                            autocomplete="organization"
                            aria-describedby="system_name_hint"
                            required
                        >
                        <p id="system_name_hint" class="settings-hint">{{ __('system-settings.system_name_hint') }}</p>
                        <x-input-error for="system_name" class="mt-1" />
                    </div>
                    <div class="col-md-6">
                        <label for="company_code" class="settings-label">
                            {{ __('system-settings.company_code') }}
                            <span class="settings-required">{{ __('system-settings.required') }}</span>
                        </label>
                        <input
                            id="company_code"
                            type="text"
                            name="company_code"
                            class="form-control @error('company_code') is-invalid @enderror ltr-nums"
                            value="{{ old('company_code', $systemSetting->company_code) }}"
                            aria-describedby="company_code_hint"
                            required
                        >
                        <p id="company_code_hint" class="settings-hint">{{ __('system-settings.company_code_hint') }}</p>
                        <x-input-error for="company_code" class="mt-1" />
                    </div>
                </div>
            </section>

            <section class="settings-card" aria-labelledby="edit-contact">
                <div class="settings-card-head">
                    <span class="settings-icon" aria-hidden="true"><i class="bi bi-geo-alt"></i></span>
                    <div>
                        <h2 id="edit-contact" class="settings-card-title">{{ __('system-settings.section_contact') }}</h2>
                        <p class="settings-card-hint">{{ __('system-settings.section_contact_hint') }}</p>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-7">
                        <label for="address" class="settings-label">{{ __('system-settings.address') }}</label>
                        <input
                            id="address"
                            type="text"
                            name="address"
                            class="form-control @error('address') is-invalid @enderror"
                            value="{{ old('address', $systemSetting->address) }}"
                            autocomplete="street-address"
                            aria-describedby="address_hint"
                        >
                        <p id="address_hint" class="settings-hint">{{ __('system-settings.address_hint') }}</p>
                        <x-input-error for="address" class="mt-1" />
                    </div>
                    <div class="col-md-5">
                        <label for="phone" class="settings-label">{{ __('system-settings.phone') }}</label>
                        <input
                            id="phone"
                            type="tel"
                            name="phone"
                            class="form-control @error('phone') is-invalid @enderror ltr-nums"
                            value="{{ old('phone', $systemSetting->phone) }}"
                            autocomplete="tel"
                            aria-describedby="phone_hint"
                        >
                        <p id="phone_hint" class="settings-hint">{{ __('system-settings.phone_hint') }}</p>
                        <x-input-error for="phone" class="mt-1" />
                    </div>
                </div>
            </section>

            <section class="settings-card" aria-labelledby="edit-notice">
                <div class="settings-card-head">
                    <span class="settings-icon" aria-hidden="true"><i class="bi bi-megaphone"></i></span>
                    <div>
                        <h2 id="edit-notice" class="settings-card-title">{{ __('system-settings.section_notice') }}</h2>
                        <p class="settings-card-hint">{{ __('system-settings.section_notice_hint') }}</p>
                    </div>
                </div>

                <label for="general_alert" class="settings-label">{{ __('system-settings.general_alert') }}</label>
                <textarea
                    id="general_alert"
                    name="general_alert"
                    rows="4"
                    class="form-control @error('general_alert') is-invalid @enderror"
                    aria-describedby="general_alert_hint"
                >{{ old('general_alert', $systemSetting->general_alert) }}</textarea>
                <p id="general_alert_hint" class="settings-hint">{{ __('system-settings.general_alert_hint') }}</p>
                <x-input-error for="general_alert" class="mt-1" />
            </section>

            <section class="settings-card" aria-labelledby="edit-status">
                <div class="settings-card-head mb-2">
                    <span class="settings-icon" aria-hidden="true"><i class="bi bi-toggles"></i></span>
                    <div>
                        <h2 id="edit-status" class="settings-card-title">{{ __('system-settings.section_status') }}</h2>
                        <p class="settings-card-hint">{{ __('system-settings.section_status_hint') }}</p>
                    </div>
                </div>

                <div class="form-check form-switch settings-switch">
                    <div>
                        <label for="active" class="settings-label mb-0">{{ __('general.active') }}</label>
                        <p id="active_hint" class="settings-hint">{{ __('system-settings.active_hint') }}</p>
                    </div>
                    <input
                        id="active"
                        type="checkbox"
                        name="active"
                        value="1"
                        role="switch"
                        class="form-check-input @error('active') is-invalid @enderror"
                        aria-describedby="active_hint"
                        @checked(old('active', $systemSetting->active))
                    >
                </div>
                <x-input-error for="active" class="mt-1" />
            </section>

            <div class="settings-actions">
                <button type="submit" class="btn btn-primary">{{ __('general.save_changes') }}</button>
                <a href="{{ route('system-settings.index') }}" class="btn btn-outline-secondary">{{ __('general.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        (() => {
            const input = document.getElementById('system_photo');
            const preview = document.getElementById('settings-photo-preview');
            const placeholder = document.getElementById('settings-photo-placeholder');

            input?.addEventListener('change', () => {
                const file = input.files?.[0];

                if (! file || ! preview) {
                    return;
                }

                preview.src = URL.createObjectURL(file);
                preview.classList.remove('d-none');
                preview.hidden = false;
                placeholder?.classList.add('d-none');
            });
        })();
    </script>
@endsection
