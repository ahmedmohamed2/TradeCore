@extends('layouts.master')

@section('title', __('roles.title'))

@section('css')
    @include('access.partials.styles')
@endsection

@section('title_page_1', __('menu.dashboard'))
@section('title_page_2', __('roles.title'))
@section('main_title', __('roles.title'))

@section('content')
    <div class="settings-shell">
        @include('access.partials.flashes')

        <div class="access-toolbar">
            <p class="settings-lead">{{ __('roles.intro') }}</p>
            @can(\App\Enums\Permission::CreateRoles->value)
                <a href="{{ route('roles.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> {{ __('roles.create') }}
                </a>
            @endcan
        </div>

        <section class="settings-card" aria-labelledby="roles-heading">
            <h2 id="roles-heading" class="visually-hidden">{{ __('roles.title') }}</h2>

            @if ($roles->isEmpty())
                <div class="settings-blank">
                    <i class="bi bi-shield-lock" aria-hidden="true"></i>
                    <p class="settings-empty-note mt-2">{{ __('roles.empty') }}</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table access-table align-middle">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('roles.name') }}</th>
                                <th scope="col">{{ __('roles.users_count') }}</th>
                                <th scope="col" class="text-end">{{ __('general.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>
                                        {{ \App\Support\RoleName::label($role->name) }}
                                        @if ($role->isSuperAdmin())
                                            <span class="badge text-bg-primary ms-1">{{ __('roles.protected') }}</span>
                                        @endif
                                    </td>
                                    <td class="ltr-nums">{{ $role->users_count }}</td>
                                    <td>
                                        <div class="access-actions">
                                            @unless ($role->isSuperAdmin())
                                                @can(\App\Enums\Permission::UpdateRoles->value)
                                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-primary">
                                                        {{ __('general.edit') }}
                                                    </a>
                                                @endcan

                                                @can(\App\Enums\Permission::DeleteRoles->value)
                                                    @if ($role->users_count === 0)
                                                        <form method="POST" action="{{ route('roles.destroy', $role) }}" onsubmit="return confirm(@js(__('roles.confirm_delete')))">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('general.delete') }}</button>
                                                        </form>
                                                    @endif
                                                @endcan
                                            @endunless
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $roles->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </section>
    </div>
@endsection
