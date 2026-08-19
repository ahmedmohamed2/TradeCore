@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill text-danger"></i>
            {{ $title }}
        </h5>
        <button type="button" class="btn-close" x-on:click="show = false" aria-label="{{ __('Close') }}"></button>
    </div>

    <div class="modal-body">
        {{ $content }}
    </div>

    <div class="modal-footer">
        {{ $footer }}
    </div>
</x-modal>
