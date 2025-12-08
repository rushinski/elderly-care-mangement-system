{{-- resources/views/components/card.blade.php --}}
@props(['title' => null, 'actions' => null])

<div class="bg-white shadow-sm rounded-lg p-4 mb-4">
    @if($title || $actions)
        <div class="flex items-center justify-between mb-3">
            @if($title)
                <h2 class="text-base md:text-lg font-semibold">{{ $title }}</h2>
            @endif
            @if($actions)
                <div>{{ $actions }}</div>
            @endif
        </div>
    @endif

    <div>
        {{ $slot }}
    </div>
</div>
