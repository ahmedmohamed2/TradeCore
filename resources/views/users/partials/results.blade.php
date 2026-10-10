@if ($users->isEmpty())
    <div class="settings-blank">
        <i class="bi bi-people" aria-hidden="true"></i>
        <p class="settings-empty-note mt-2">
            {{ $search !== '' ? __('users.empty') : __('users.empty_directory') }}
        </p>
    </div>
@else
    <div class="table-responsive">
        <table class="table access-table align-middle">
            <thead>
                <tr>
                    <th scope="col">{{ __('users.name') }}</th>
                    <th scope="col">{{ __('users.email') }}</th>
                    <th scope="col">{{ __('users.roles') }}</th>
                    <th scope="col" class="text-end">{{ __('general.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $account)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $account->profileImageUrl() }}" alt="" class="profile-thumb">
                                <span>{{ $account->name }}</span>
                            </div>
                        </td>
                        <td class="ltr-nums" dir="ltr">{{ $account->email }}</td>
                        <td>
                            @if ($account->roles->isEmpty())
                                <span class="text-secondary">{{ __('users.none') }}</span>
                            @else
                                <div class="role-pills">
                                    @foreach ($account->roles as $role)
                                        <span class="badge {{ $role->isSuperAdmin() ? 'text-bg-primary' : 'text-bg-secondary' }}">
                                            {{ \App\Support\RoleName::label($role->name) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="access-actions">
                                @can(\App\Enums\Permission::UpdateUsers->value)
                                    @if (! $account->hasRole(\App\Support\RoleName::SuperAdmin) || auth()->user()->hasRole(\App\Support\RoleName::SuperAdmin))
                                        <a href="{{ route('users.edit', $account) }}" class="btn btn-sm btn-outline-primary">
                                            {{ __('general.edit') }}
                                        </a>
                                    @endif
                                @endcan

                                @can(\App\Enums\Permission::DeleteUsers->value)
                                    @if (! auth()->user()->is($account) && (! $account->hasRole(\App\Support\RoleName::SuperAdmin) || ($superAdminCount > 1 && auth()->user()->hasRole(\App\Support\RoleName::SuperAdmin))))
                                        <form method="POST" action="{{ route('users.destroy', $account) }}" onsubmit="return confirm(@js(__('users.confirm_delete')))">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('general.delete') }}</button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
@endif
