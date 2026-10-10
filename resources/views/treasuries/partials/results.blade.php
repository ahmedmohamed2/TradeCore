@if ($treasuries->isEmpty())
    <div class="settings-blank">
        <i class="bi bi-safe" aria-hidden="true"></i>
        <p class="settings-empty-note mt-2">
            {{ $search !== '' ? __('treasuries.empty') : __('treasuries.empty_directory') }}
        </p>
    </div>
@else
    <div class="table-responsive">
        <table class="table access-table align-middle">
            <thead>
                <tr>
                    <th scope="col">{{ __('treasuries.code') }}</th>
                    <th scope="col">{{ __('treasuries.name') }}</th>
                    <th scope="col">{{ __('treasuries.master') }}</th>
                    <th scope="col">{{ __('treasuries.opening_balance') }}</th>
                    <th scope="col">{{ __('treasuries.last_collection_number') }}</th>
                    <th scope="col">{{ __('treasuries.last_payment_number') }}</th>
                    <th scope="col">{{ __('general.active') }}</th>
                    <th scope="col" class="text-end">{{ __('general.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($treasuries as $treasury)
                    <tr>
                        <td class="ltr-nums" dir="ltr">{{ $treasury->code }}</td>
                        <td>{{ $treasury->name }}</td>
                        <td>
                            @if ($treasury->is_master)
                                <span class="badge text-bg-primary">{{ __('treasuries.yes') }}</span>
                            @else
                                <span class="text-secondary">{{ __('treasuries.no') }}</span>
                            @endif
                        </td>
                        <td class="ltr-nums" dir="ltr">{{ number_format((float) $treasury->opening_balance, 2) }}</td>
                        <td class="ltr-nums" dir="ltr">{{ $treasury->last_collection_number }}</td>
                        <td class="ltr-nums" dir="ltr">{{ $treasury->last_payment_number }}</td>
                        <td>
                            @if ($treasury->active)
                                <span class="badge text-bg-success">{{ __('general.active') }}</span>
                            @else
                                <span class="badge text-bg-secondary">{{ __('general.inactive') }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="access-actions">
                                @can(\App\Enums\Permission::UpdateTreasuries->value)
                                    <a href="{{ route('treasuries.edit', $treasury) }}" class="btn btn-sm btn-outline-primary">
                                        {{ __('general.edit') }}
                                    </a>
                                @endcan

                                @can(\App\Enums\Permission::DeleteTreasuries->value)
                                    <form method="POST" action="{{ route('treasuries.destroy', $treasury) }}" onsubmit="return confirm(@js(__('treasuries.confirm_delete')))">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('general.delete') }}</button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $treasuries->links('pagination::bootstrap-5') }}
    </div>
@endif
