@props(['id', 'maxWidth'])

@php
$id = $id ?? md5($attributes->wire('model'));

$maxWidth = [
    'sm' => 'modal-sm',
    'md' => '',
    'lg' => 'modal-lg',
    'xl' => 'modal-xl',
    '2xl' => 'modal-xl',
][$maxWidth ?? '2xl'];
@endphp

<div
    x-data="{ show: @entangle($attributes->wire('model')) }"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    id="{{ $id }}"
>
    <div
        x-show="show"
        x-cloak
        class="modal fade"
        :class="{ 'show d-block': show }"
        tabindex="-1"
        role="dialog"
        aria-modal="true"
        style="display: none;"
    >
        <div class="modal-dialog {{ $maxWidth }} modal-dialog-centered" x-on:click.stop>
            <div class="modal-content" x-trap.inert.noscroll="show">
                {{ $slot }}
            </div>
        </div>
    </div>

    <div
        x-show="show"
        x-cloak
        class="modal-backdrop fade"
        :class="{ 'show': show }"
        x-on:click="show = false"
        style="display: none;"
    ></div>
</div>
