@extends('layouts.master')

@section('title', __('roles.edit_title'))

@section('css')
    @include('access.partials.styles')
@endsection

@section('title_page_1', __('roles.title'))
@section('title_page_2', __('roles.edit_title'))
@section('main_title', __('roles.edit_title'))

@section('content')
    <div class="settings-shell">
        <a href="{{ route('roles.index') }}" class="settings-back">
            <i class="bi bi-arrow-left"></i>
            {{ __('roles.back') }}
        </a>

        @include('roles.partials.form')
    </div>
@endsection
