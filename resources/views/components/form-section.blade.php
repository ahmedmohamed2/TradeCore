@props(['submit'])

<div {{ $attributes->merge(['class' => 'card mb-4']) }}>
    <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
        @if (isset($description) && filled($description))
            <div class="card-tools">
                <span class="text-muted">{{ $description }}</span>
            </div>
        @endif
    </div>

    <form wire:submit="{{ $submit }}">
        <div class="card-body">
            {{ $form }}
        </div>

        @if (isset($actions))
            <div class="card-footer d-flex justify-content-end align-items-center gap-2">
                {{ $actions }}
            </div>
        @endif
    </form>
</div>
