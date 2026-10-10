@extends('layouts.master')

@section('title', __('treasuries.title'))

@section('css')
    @include('access.partials.styles')
@endsection

@section('title_page_1', __('menu.accounting'))
@section('title_page_2', __('treasuries.title'))
@section('main_title', __('treasuries.title'))

@section('content')
    <div class="settings-shell">
        @include('access.partials.flashes')

        <div class="access-toolbar">
            <p class="settings-lead">{{ __('treasuries.intro') }}</p>
            @can(\App\Enums\Permission::CreateTreasuries->value)
                <a href="{{ route('treasuries.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> {{ __('treasuries.create') }}
                </a>
            @endcan
        </div>

        <section class="settings-card" aria-labelledby="treasuries-heading">
            <h2 id="treasuries-heading" class="visually-hidden">{{ __('treasuries.title') }}</h2>

            <x-realtime-search
                :action="route('treasuries.index')"
                results-id="treasuries-results"
                :value="$search"
                :placeholder="__('treasuries.search_placeholder')"
            />

            <div id="treasuries-results" class="realtime-search-results" aria-live="polite">
                @fragment('results')
                    @include('treasuries.partials.results')
                @endfragment
            </div>
        </section>
    </div>
@endsection
