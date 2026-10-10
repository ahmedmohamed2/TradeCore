@extends('layouts.master')

@section('title', __('users.title'))

@section('css')
    @include('access.partials.styles')
@endsection

@section('title_page_1', __('menu.dashboard'))
@section('title_page_2', __('users.title'))
@section('main_title', __('users.title'))

@section('content')
    <div class="settings-shell">
        @include('access.partials.flashes')

        <div class="access-toolbar">
            <p class="settings-lead">{{ __('users.intro') }}</p>
            @can(\App\Enums\Permission::CreateUsers->value)
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> {{ __('users.create') }}
                </a>
            @endcan
        </div>

        <section class="settings-card" aria-labelledby="users-heading">
            <h2 id="users-heading" class="visually-hidden">{{ __('users.title') }}</h2>

            <x-realtime-search
                :action="route('users.index')"
                results-id="users-results"
                :value="$search"
                :placeholder="__('users.search_placeholder')"
            />

            <div id="users-results" class="realtime-search-results" aria-live="polite">
                @fragment('results')
                    @include('users.partials.results')
                @endfragment
            </div>
        </section>
    </div>
@endsection
