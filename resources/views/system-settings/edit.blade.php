@extends('layouts.master')

@section('title', 'Edit System Settings')

@section("css")


@endsection

@section("title_page_1", "System Settings")

@section("title_page_2", "Edit")

@section("main_title", "Edit System Settings")


@section("content")

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit System Settings</h3>
            </div>
            <div class="card-body">
                <x-validation-errors class="mb-3" />

                <form method="POST" action="{{ route('system-settings.update', $systemSetting) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="system_name" class="form-label">System Name</label>
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
                        <label for="company_code" class="form-label">Company Code</label>
                        <input
                            id="company_code"
                            type="text"
                            name="company_code"
                            class="form-control @error('company_code') is-invalid @enderror"
                            value="{{ old('company_code', $systemSetting->company_code) }}"
                            required
                        >
                        <x-input-error for="company_code" class="mt-1" />
                    </div>

                    <div class="mb-3">
                        <label for="system_photo" class="form-label">System Photo</label>
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
                        <label for="address" class="form-label">Address</label>
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
                        <label for="phone" class="form-label">Phone</label>
                        <input
                            id="phone"
                            type="text"
                            name="phone"
                            class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $systemSetting->phone) }}"
                        >
                        <x-input-error for="phone" class="mt-1" />
                    </div>

                    <div class="mb-3">
                        <label for="general_alert" class="form-label">General Alert</label>
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
                        <label for="active" class="form-check-label">Active</label>
                        <x-input-error for="active" class="mt-1" />
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="{{ route('system-settings.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection


@section("scripts")


@endsection
