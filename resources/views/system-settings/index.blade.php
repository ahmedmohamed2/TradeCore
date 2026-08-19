@extends('layouts.master')

@section('title', 'System Settings')

@section("css")


@endsection

@section("title_page_1", "Dashboard")

@section("title_page_2", "System Settings")

@section("main_title", "System Settings")


@section("content")

<div class="row">
    <div class="col-md-12">
        @session('status')
            <div class="alert alert-success" role="alert">
                {{ $value }}
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
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="w-25">System Name</th>
                                <td>{{ $systemSettings->system_name }}</td>
                            </tr>
                            <tr>
                                <th>System Photo</th>
                                <td>
                                    @if ($systemSettings->system_photo)
                                        <img src="{{ asset('uploads/company_photos/' . $systemSettings->system_photo) }}" alt="{{ $systemSettings->system_name }}" class="img-fluid" style="width: 100px; height: 100px;">
                                    @else
                                        <span class="text-muted">No photo</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Active</th>
                                <td>{{ $systemSettings->active ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <th>General Alert</th>
                                <td>{{ $systemSettings->general_alert }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $systemSettings->address }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $systemSettings->phone }}</td>
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <td>{{ $systemSettings->createdBy?->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Updated By</th>
                                <td>{{ $systemSettings->updatedBy?->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Company Code</th>
                                <td>{{ $systemSettings->company_code }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $systemSettings->created_at }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $systemSettings->updated_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <p class="mb-0 text-muted">No active system settings found.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection


@section("scripts")


@endsection
