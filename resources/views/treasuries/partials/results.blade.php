@if ($treasuries->isEmpty())
    <div class="settings-blank">
        <i class="bi bi-safe" aria-hidden="true"></i>
        <p class="settings-empty-note mt-2">
            {{ $search !== '' ? __('treasuries.empty') : __('treasuries.empty_directory') }}
        </p>
    </div>
@else
    <div class="treasury-list">
        @foreach ($treasuries as $treasury)
            <article class="treasury-card">
                <header class="treasury-card-head">
                    <div class="treasury-identity">
                        <p class="treasury-code ltr-nums" dir="ltr">{{ $treasury->code }}</p>
                        <h3 class="treasury-name">{{ $treasury->name }}</h3>
                    </div>

                    <div class="treasury-badges">
                        @if ($treasury->active)
                            <span class="badge text-bg-success">{{ __('general.active') }}</span>
                        @else
                            <span class="badge text-bg-secondary">{{ __('general.inactive') }}</span>
                        @endif
                    </div>

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
                </header>

                <dl class="treasury-facts">
                    <div>
                        <dt>{{ __('treasuries.master') }}</dt>
                        <dd>
                            @if ($treasury->is_master)
                                <span class="badge text-bg-primary">{{ __('treasuries.yes') }}</span>
                            @else
                                {{ __('treasuries.no') }}
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt>{{ __('treasuries.opening_balance') }}</dt>
                        <dd class="ltr-nums" dir="ltr">{{ number_format((float) $treasury->opening_balance, 2) }}</dd>
                    </div>
                    <div>
                        <dt>{{ __('treasuries.last_collection_number') }}</dt>
                        <dd class="ltr-nums" dir="ltr">{{ $treasury->last_collection_number }}</dd>
                    </div>
                    <div>
                        <dt>{{ __('treasuries.last_payment_number') }}</dt>
                        <dd class="ltr-nums" dir="ltr">{{ $treasury->last_payment_number }}</dd>
                    </div>
                </dl>

                <div class="treasury-notes">
                    <span class="settings-kicker">{{ __('treasuries.notes') }}</span>
                    @if (filled($treasury->notes))
                        <p class="treasury-notes-text">{{ $treasury->notes }}</p>
                    @else
                        <p class="settings-empty-note">—</p>
                    @endif
                </div>

                <p class="treasury-record">
                    <span>
                        {{ __('treasuries.created_by') }}
                        <strong>{{ $treasury->createdBy?->name ?? '—' }}</strong>
                        <time class="ltr-nums" dir="ltr" datetime="{{ $treasury->created_at?->toIso8601String() }}">{{ $treasury->created_at?->translatedFormat('d M Y, h:i A') ?? '—' }}</time>
                    </span>
                    <span>
                        {{ __('treasuries.updated_by') }}
                        <strong>{{ $treasury->updatedBy?->name ?? '—' }}</strong>
                        <time class="ltr-nums" dir="ltr" datetime="{{ $treasury->updated_at?->toIso8601String() }}">{{ $treasury->updated_at?->translatedFormat('d M Y, h:i A') ?? '—' }}</time>
                    </span>
                </p>
            </article>
        @endforeach
    </div>

    <div class="mt-3">
        {{ $treasuries->links('pagination::bootstrap-5') }}
    </div>
@endif
