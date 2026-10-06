@session('status')
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ $value }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('general.close') }}"></button>
    </div>
@endsession

@session('error')
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $value }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('general.close') }}"></button>
    </div>
@endsession
