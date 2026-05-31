{{-- OVNEX Haber Öğesi Bileşeni --}}
@props(['haber'])
<div class="border-b border-[#30363d] pb-2 mb-2 last:border-0 last:mb-0 last:pb-0">
    <a href="{{ $haber['external_url'] ?? '#' }}" target="_blank" rel="noopener" class="block hover:bg-[#1c2128] -mx-2 px-2 py-1 rounded transition">
        <p class="text-gray-200 text-xs leading-relaxed line-clamp-2">
            @if ($haber['category'] ?? false)
                <span class="text-[{{ $haber['category'] === 'earthquake' ? '#ff6b35' : ($haber['category'] === 'traffic' ? '#f85149' : ($haber['category'] === 'fire' ? '#ff6b35' : '#00d4ff')) }}] text-[10px] uppercase mr-1">[{{ $haber['category'] }}]</span>
            @endif
            {{ $haber['title'] ?? '' }}
        </p>
        <div class="flex justify-between items-center mt-1">
            <span class="text-[10px] text-gray-500">{{ $haber['source_name'] ?? '' }}</span>
            <span class="text-[10px] text-gray-600">{{ $haber['province'] ?? '' }}</span>
        </div>
    </a>
</div>
