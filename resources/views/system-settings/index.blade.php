@extends('layouts.master')

@section('title', 'System Settings')

@section("css")
<style>
    .settings-avatar {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: .5rem;
        border: 1px solid #e9ecef;
    }
    .settings-avatar-placeholder {
        width: 90px;
        height: 90px;
        border-radius: .5rem;
        background: #f8f9fa;
        border: 1px dashed #ced4da;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        font-size: 1.75rem;
    }
    .settings-label {
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #6c757d;
        margin-bottom: .15rem;
    }
    .settings-value {
        font-size: .95rem;
        font-weight: 500;
        word-break: break-word;
    }
</style>
@endsection

@section("title_page_1", "Dashboard")
@section("title_page_2", "System Settings")
@section("main_title", "System Settings")

@section("content")

<div class="row">
    <div class="col-md-12">
        @session('status')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $value }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endsession

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">System Settings</h3>
                @if ($systemSettings)
                    <a href="{{ route('system-settings.edit', $systemSettings) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                @endif
            </div>

            <div class="card-body">
                @if ($systemSettings)

                    {{-- Header block: photo + name + status --}}
                    <div class="d-flex align-items-center gap-3 mb-4 pb-4 border-bottom">
                        @if ($systemSettings->system_photo)
                            <img src="{{ asset('uploads/company_photos/' . $systemSettings->system_photo) }}" alt="{{ $systemSettings->system_name }}" class="settings-avatar">
                        @else
                            <div class="settings-avatar-placeholder">
                                <i class="bi bi-building"></i>
                            </div>
                        @endif

                        <div>
                            <h4 class="mb-1">{{ $systemSettings->system_name ?? '—' }}</h4>
                            <div class="d-flex align-items-center gap-2">
                                @if ($systemSettings->active)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle"></i> Inactive
                                    </span>
                                @endif
                                @if ($systemSettings->company_code)
                                    <span class="badge bg-light text-dark border">
                                        {{ $systemSettings->company_code }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- General alert, if present --}}
                    @if ($systemSettings->general_alert)
                        <div class="alert alert-warning d-flex align-items-start gap-2" role="alert">
                            <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                            <div>
                                <strong>General Alert</strong>
                                <div>{{ $systemSettings->general_alert }}</div>
                            </div>
                        </div>
                    @endif

                    {{-- Details grid --}}
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="settings-label">Address</div>
                            <div class="settings-value">
                                <i class="bi bi-geo-alt text-muted"></i>
                                {{ $systemSettings->address ?: '—' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="settings-label">Phone</div>
                            <div class="settings-value">
                                <i class="bi bi-telephone text-muted"></i>
                                {{ $systemSettings->phone ?: '—' }}
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Audit info --}}
                    <div class="row g-4">
                        <div class="col-md-3 col-6">
                            <div class="settings-label">Created By</div>
                            <div class="settings-value">{{ $systemSettings->createdBy?->name ?? '—' }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="settings-label">Created At</div>
                            <div class="settings-value" title="{{ $systemSettings->created_at }}">
                                {{ $systemSettings->created_at?->format('d M Y, h:i A') ?? '—' }}
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="settings-label">Updated By</div>
                            <div class="settings-value">{{ $systemSettings->updatedBy?->name ?? '—' }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="settings-label">Updated At</div>
                            <div class="settings-value" title="{{ $systemSettings->updated_at }}">
                                {{ $systemSettings->updated_at?->format('d M Y, h:i A') ?? '—' }}
                            </div>
                        </div>
                    </div>

                @else
                    <div class="text-center py-5">
                        <i class="bi bi-gear display-4 text-muted"></i>
                        <p class="mb-3 mt-3 text-muted">No active system settings found.</p>
                        <a href="{{ route('system-settings.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Create System Settings
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section("scripts")
@endsection