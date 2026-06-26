@props(['items' => []])

@if(count($items) > 0)
<nav class="flex items-center gap-1.5 text-sm text-gray-400 mb-4">
    @foreach($items as $i => $item)
        @if($i > 0)
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        @endif

        @if(isset($item['route']) && $i < count($items) - 1)
            <a href="{{ route($item['route'], $item['params'] ?? []) }}"
               class="hover:text-gray-600 transition-colors">{{ $item['label'] }}</a>
        @else
            <span class="{{ $i === count($items) - 1 ? 'text-gray-600 font-medium' : '' }}">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
@endif
