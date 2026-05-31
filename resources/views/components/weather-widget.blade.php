{{-- OVNEX Hava Durumu Widget'ı --}}
@php
    $hava = $sonHava ?? null;
@endphp
<h3 class="text-sm font-semibold ovnex-cyan mb-2 tracking-wider">HAVA DURUMU</h3>
<div class="flex items-center gap-3">
    @if ($hava)
        <div class="text-3xl">{{ $hava->temperature_c ?? '--' }}°</div>
        <div>
            <div class="text-gray-200 text-sm">{{ $hava->condition_text ?? '--' }}</div>
            <div class="text-gray-500 text-[10px]">Nem: %{{ $hava->humidity_pct ?? '--' }} | Rüzgar: {{ $hava->wind_speed_ms ?? '--' }} m/s</div>
        </div>
    @else
        <div class="text-gray-500 text-xs">Hava durumu verisi bekleniyor...</div>
    @endif
</div>
