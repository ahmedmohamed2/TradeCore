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
                            @if ($treasury->children->isNotEmpty())
                                <span class="treasury-delete-note">{{ __('treasuries.delete_blocked') }}</span>
                            @else
                                <form method="POST" action="{{ route('treasuries.destroy', $treasury) }}" onsubmit="return confirm(@js(__('treasuries.confirm_delete')))">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('general.delete') }}</button>
                                </form>
                            @endif
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

                <section class="treasury-subs" aria-labelledby="treasury-subs-{{ $treasury->id }}">
                    <div class="treasury-subs-head">
                        <h4 id="treasury-subs-{{ $treasury->id }}" class="treasury-subs-title">{{ __('treasuries.sub_treasuries') }}</h4>
                        @can(\App\Enums\Permission::CreateTreasuries->value)
                            <a href="{{ route('treasuries.create', ['parent' => $treasury->id]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                                {{ __('treasuries.add_sub') }}
                            </a>
                        @endcan
                    </div>

                    @if ($treasury->children->isEmpty())
                        <p class="settings-empty-note">{{ __('treasuries.no_subs') }}</p>
                    @else
                        <ul class="treasury-sub-list">
                            @foreach ($treasury->children as $subTreasury)
                                <li class="treasury-sub">
                                    <div class="treasury-sub-identity">
                                        <p class="treasury-code ltr-nums" dir="ltr">{{ $subTreasury->code }}</p>
                                        <p class="treasury-sub-name">{{ $subTreasury->name }}</p>
                                        <p class="treasury-sub-meta">
                                            <span class="ltr-nums" dir="ltr">{{ number_format((float) $subTreasury->opening_balance, 2) }}</span>
                                            <span>{{ __('treasuries.last_collection_number') }} <span class="ltr-nums" dir="ltr">{{ $subTreasury->last_collection_number }}</span></span>
                                            <span>{{ __('treasuries.last_payment_number') }} <span class="ltr-nums" dir="ltr">{{ $subTreasury->last_payment_number }}</span></span>
                                        </p>
                                        @if (filled($subTreasury->notes))
                                            <p class="treasury-sub-notes">{{ $subTreasury->notes }}</p>
                                        @endif
                                    </div>

                                    <div class="treasury-badges">
                                        @if ($subTreasury->active)
                                            <span class="badge text-bg-success">{{ __('general.active') }}</span>
                                        @else
                                            <span class="badge text-bg-secondary">{{ __('general.inactive') }}</span>
                                        @endif
                                    </div>

                                    <div class="access-actions">
                                        @can(\App\Enums\Permission::UpdateTreasuries->value)
                                            <a href="{{ route('treasuries.edit', $subTreasury) }}" class="btn btn-sm btn-outline-primary">
                                                {{ __('general.edit') }}
                                            </a>
                                        @endcan

                                        @can(\App\Enums\Permission::DeleteTreasuries->value)
                                            <form method="POST" action="{{ route('treasuries.destroy', $subTreasury) }}" onsubmit="return confirm(@js(__('treasuries.confirm_delete')))">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('general.delete') }}</button>
                                            </form>
                                        @endcan
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

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
