@extends('layouts.master')

@section('title', __('Profile'))
@section('main_title', __('Profile'))
@section('title_page_1', __('Account'))
@section('title_page_2', __('Profile'))

@section('content')
    @if (Laravel\Fortify\Features::canUpdateProfileInformation())
        @livewire('profile.update-profile-information-form')
    @endif

    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
        @livewire('profile.update-password-form')
    @endif

    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
        @livewire('profile.two-factor-authentication-form')
    @endif

    @livewire('profile.logout-other-browser-sessions-form')

    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
        @livewire('profile.delete-user-form')
    @endif
@endsection
