@extends('layouts.master')

@section('title', __('users.create_title'))

@section('css')
    @include('access.partials.styles')
@endsection

@section('title_page_1', __('users.title'))
@section('title_page_2', __('users.create_title'))
@section('main_title', __('users.create_title'))

@section('content')
    <div class="settings-shell">
        <a href="{{ route('users.index') }}" class="settings-back">
            <i class="bi bi-arrow-left"></i>
            {{ __('users.back') }}
        </a>

        @include('users.partials.form', ['user' => null])
    </div>
@endsection
