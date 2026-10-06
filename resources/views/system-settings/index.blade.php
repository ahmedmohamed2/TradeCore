@extends('layouts.master')

@section('title', __('system-settings.title'))

@section('css')
    @include('system-settings.partials.styles')
@endsection

@section('title_page_1', __('menu.dashboard'))
@section('title_page_2', __('system-settings.title'))
@section('main_title', __('system-settings.title'))

@section('content')
    <div class="settings-shell">
        @session('status')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $value }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('general.close') }}"></button>
            </div>
        @endsession

        @if ($systemSettings)
            <p class="settings-lead">{{ __('system-settings.intro') }}</p>

            <section class="settings-card" aria-labelledby="settings-company">
                <div class="settings-hero">
                    <div class="settings-identity">
                        @if ($systemSettings->system_photo)
                            <img
                                src="{{ asset('uploads/company_photos/' . $systemSettings->system_photo) }}"
                                alt="{{ $systemSettings->system_name }}"
                                class="settings-photo"
                            >
                        @else
                            <div class="settings-photo-placeholder" aria-hidden="true">
                                <i class="bi bi-building"></i>
                            </div>
                        @endif

                        <div>
                            <h2 id="settings-company" class="settings-name">{{ $systemSettings->system_name ?: '—' }}</h2>
                            <div class="settings-meta">
                                @if ($systemSettings->active)
                                    <span class="settings-pill settings-pill-ok">
                                        <i class="bi bi-check-circle"></i> {{ __('general.active') }}
                                    </span>
                                @else
                                    <span class="settings-pill settings-pill-off">
                                        <i class="bi bi-pause-circle"></i> {{ __('general.inactive') }}
                                    </span>
                                @endif
                                @if ($systemSettings->company_code)
                                    <span class="settings-pill settings-pill-code ltr-nums">{{ $systemSettings->company_code }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('system-settings.edit', $systemSettings) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square"></i> {{ __('general.edit') }}
                    </a>
                </div>
            </section>

            <div class="row g-3">
                <div class="col-md-6">
                    <section class="settings-card h-100" aria-labelledby="settings-contact">
                        <div class="settings-card-head">
                            <span class="settings-icon" aria-hidden="true"><i class="bi bi-telephone"></i></span>
                            <div>
                                <h3 id="settings-contact" class="settings-card-title">{{ __('system-settings.section_contact') }}</h3>
                                <p class="settings-card-hint">{{ __('system-settings.section_contact_hint') }}</p>
                            </div>
                        </div>
                        <div class="settings-field">
                            <span class="settings-kicker">{{ __('system-settings.address') }}</span>
                            <p class="settings-value">{{ $systemSettings->address ?: '—' }}</p>
                        </div>
                        <div class="settings-field mb-0">
                            <span class="settings-kicker">{{ __('system-settings.phone') }}</span>
                            <p class="settings-value ltr-nums">{{ $systemSettings->phone ?: '—' }}</p>
                        </div>
                    </section>
                </div>

                <div class="col-md-6">
                    <section class="settings-card h-100" aria-labelledby="settings-notice">
                        <div class="settings-card-head">
                            <span class="settings-icon" aria-hidden="true"><i class="bi bi-megaphone"></i></span>
                            <div>
                                <h3 id="settings-notice" class="settings-card-title">{{ __('system-settings.section_notice') }}</h3>
                                <p class="settings-card-hint">{{ __('system-settings.section_notice_hint') }}</p>
                            </div>
                        </div>
                        @if ($systemSettings->general_alert)
                            <div class="settings-notice" role="status">
                                <i class="bi bi-exclamation-triangle mt-1" aria-hidden="true"></i>
                                <div>
                                    <strong>{{ __('system-settings.general_alert') }}</strong>
                                    <div>{{ $systemSettings->general_alert }}</div>
                                </div>
                            </div>
                        @else
                            <p class="settings-empty-note">{{ __('system-settings.empty_alert') }}</p>
                        @endif
                    </section>
                </div>
            </div>

            <section class="settings-card" aria-labelledby="settings-record">
                <div class="settings-card-head">
                    <span class="settings-icon" aria-hidden="true"><i class="bi bi-clock-history"></i></span>
                    <div>
                        <h3 id="settings-record" class="settings-card-title">{{ __('system-settings.section_record') }}</h3>
                        <p class="settings-card-hint">{{ __('system-settings.section_record_hint') }}</p>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <span class="settings-kicker">{{ __('system-settings.created_by') }}</span>
                        <p class="settings-value">{{ $systemSettings->createdBy?->name ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <span class="settings-kicker">{{ __('system-settings.created_at') }}</span>
                        <p class="settings-value ltr-nums" title="{{ $systemSettings->created_at }}">
                            {{ $systemSettings->created_at?->translatedFormat('d M Y, h:i A') ?? '—' }}
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <span class="settings-kicker">{{ __('system-settings.updated_by') }}</span>
                        <p class="settings-value">{{ $systemSettings->updatedBy?->name ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <span class="settings-kicker">{{ __('system-settings.updated_at') }}</span>
                        <p class="settings-value ltr-nums" title="{{ $systemSettings->updated_at }}">
                            {{ $systemSettings->updated_at?->translatedFormat('d M Y, h:i A') ?? '—' }}
                        </p>
                    </div>
                </div>
            </section>
        @else
            <section class="settings-card settings-blank">
                <i class="bi bi-gear" aria-hidden="true"></i>
                <p class="settings-lead mt-3">{{ __('system-settings.no_active') }}</p>
                @if (Route::has('system-settings.create'))
                    <a href="{{ route('system-settings.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> {{ __('system-settings.create') }}
                    </a>
                @endif
            </section>
        @endif
    </div>
@endsection
