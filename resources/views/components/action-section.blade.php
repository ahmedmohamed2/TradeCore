<div {{ $attributes->merge(['class' => 'card mb-4']) }}>
    <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
        @if (isset($description) && filled($description))
            <div class="card-tools">
                <span class="text-muted">{{ $description }}</span>
            </div>
        @endif
    </div>

    <div class="card-body">
        {{ $content }}
    </div>
</div>
