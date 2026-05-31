{{-- OVNEX Uçak Popup İçeriği -- Leaflet marker popup'ında kullanılır --}}
@props(['aircraft' => []])
<div class="text-xs" style="font-family: 'JetBrains Mono', monospace; background: #161b22; color: #c9d1d9;">
    <div class="font-bold ovnex-cyan text-sm mb-1">{{ $aircraft['callsign'] ?? $aircraft['icao24'] ?? 'Bilinmiyor' }}</div>
    <div class="grid grid-cols-2 gap-x-3 gap-y-1">
        <span class="text-gray-500">Ülke:</span><span>{{ $aircraft['origin_country'] ?? '--' }}</span>
        <span class="text-gray-500">İrtifa:</span><span>{{ $aircraft['altitude_baro'] ?? '--' }} m</span>
        <span class="text-gray-500">Hız:</span><span>{{ isset($aircraft['velocity']) ? round($aircraft['velocity'] * 3.6) . ' km/h' : '--' }}</span>
        <span class="text-gray-500">Yön:</span><span>{{ $aircraft['heading'] ?? '--' }}°</span>
        <span class="text-gray-500">ICAO:</span><span class="text-[10px]">{{ $aircraft['icao24'] ?? '--' }}</span>
    </div>
</div>
