@extends('layouts.master')

@section('title', __('treasuries.create_title'))

@section('css')
    @include('access.partials.styles')
@endsection

@section('title_page_1', __('treasuries.title'))
@section('title_page_2', __('treasuries.create_title'))
@section('main_title', __('treasuries.create_title'))

@section('content')
    <div class="settings-shell">
        <a href="{{ route('treasuries.index') }}" class="settings-back">
            <i class="bi bi-arrow-left"></i>
            {{ __('treasuries.back') }}
        </a>

        @include('treasuries.partials.form', ['treasury' => null])
    </div>
@endsection
