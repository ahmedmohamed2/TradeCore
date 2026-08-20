@extends('layouts.master')

@section('title', __('system-settings.edit_title'))

@section("css")


@endsection

@section("title_page_1", __('system-settings.title'))

@section("title_page_2", __('general.edit'))

@section("main_title", __('system-settings.edit_title'))


@section("content")

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('system-settings.edit_title') }}</h3>
            </div>
            <div class="card-body">
                <x-validation-errors class="mb-3" />

                <form method="POST" action="{{ route('system-settings.update', $systemSetting) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="system_name" class="form-label">{{ __('system-settings.system_name') }}</label>
                        <input
                            id="system_name"
                            type="text"
                            name="system_name"
                            class="form-control @error('system_name') is-invalid @enderror"
                            value="{{ old('system_name', $systemSetting->system_name) }}"
                            required
                        >
                        <x-input-error for="system_name" class="mt-1" />
                    </div>

                    <div class="mb-3">
                        <label for="company_code" class="form-label">{{ __('system-settings.company_code') }}</label>
                        <input
                            id="company_code"
                            type="text"
                            name="company_code"
                            class="form-control @error('company_code') is-invalid @enderror ltr-nums"
                            value="{{ old('company_code', $systemSetting->company_code) }}"
                            required
                        >
                        <x-input-error for="company_code" class="mt-1" />
                    </div>

                    <div class="mb-3">
                        <label for="system_photo" class="form-label">{{ __('system-settings.system_photo') }}</label>
                        @if ($systemSetting->system_photo)
                            <div class="mb-2">
                                <img src="{{ asset('uploads/company_photos/' . $systemSetting->system_photo) }}" alt="{{ $systemSetting->system_name }}" class="img-fluid" style="width: 100px; height: 100px;">
                            </div>
                        @endif
                        <input
                            id="system_photo"
                            type="file"
                            name="system_photo"
                            class="form-control @error('system_photo') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/webp"
                        >
                        <x-input-error for="system_photo" class="mt-1" />
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">{{ __('system-settings.address') }}</label>
                        <input
                            id="address"
                            type="text"
                            name="address"
                            class="form-control @error('address') is-invalid @enderror"
                            value="{{ old('address', $systemSetting->address) }}"
                        >
                        <x-input-error for="address" class="mt-1" />
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">{{ __('system-settings.phone') }}</label>
                        <input
                            id="phone"
                            type="text"
                            name="phone"
                            class="form-control @error('phone') is-invalid @enderror ltr-nums"
                            value="{{ old('phone', $systemSetting->phone) }}"
                        >
                        <x-input-error for="phone" class="mt-1" />
                    </div>

                    <div class="mb-3">
                        <label for="general_alert" class="form-label">{{ __('system-settings.general_alert') }}</label>
                        <textarea
                            id="general_alert"
                            name="general_alert"
                            rows="3"
                            class="form-control @error('general_alert') is-invalid @enderror"
                        >{{ old('general_alert', $systemSetting->general_alert) }}</textarea>
                        <x-input-error for="general_alert" class="mt-1" />
                    </div>

                    <div class="mb-3 form-check">
                        <input
                            id="active"
                            type="checkbox"
                            name="active"
                            value="1"
                            class="form-check-input @error('active') is-invalid @enderror"
                            @checked(old('active', $systemSetting->active))
                        >
                        <label for="active" class="form-check-label">{{ __('general.active') }}</label>
                        <x-input-error for="active" class="mt-1" />
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ __('general.save_changes') }}</button>
                        <a href="{{ route('system-settings.index') }}" class="btn btn-secondary">{{ __('general.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection


@section("scripts")


@endsection
