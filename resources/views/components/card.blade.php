{{-- resources/views/components/card.blade.php --}}
@props(['title' => null])

<div class="bg-white shadow-sm rounded-lg p-4 mb-4">
    @if($title)
        <h2 class="text-base md:text-lg font-semibold mb-3">{{ $title }}</h2>
    @endif

    {{ $slot }}
</div>
