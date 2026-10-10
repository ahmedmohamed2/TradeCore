@props(['at' => null])

{{-- Isolate each part so Arabic letters cannot drag the day away from the month. --}}
@if ($at)
    <time
        datetime="{{ $at->toIso8601String() }}"
        {{ $attributes }}
    ><bdi dir="ltr">{{ $at->translatedFormat('d') }}</bdi> <bdi>{{ $at->translatedFormat('M') }}</bdi> <bdi dir="ltr">{{ $at->translatedFormat('Y') }}</bdi>, <bdi dir="ltr">{{ $at->translatedFormat('h:i') }}</bdi> <bdi>{{ $at->translatedFormat('A') }}</bdi></time>
@else
    <span {{ $attributes }}>—</span>
@endif
