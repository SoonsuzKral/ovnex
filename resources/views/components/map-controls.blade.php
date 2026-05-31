{{-- OVNEX Harita Kontrolleri -- Header içinde gösterilir --}}
<div class="flex items-center gap-2">
    <button id="map-zoom-in" class="px-2 py-1 ovnex-card text-xs hover:bg-[#21262d] transition" title="Yakınlaştır">+</button>
    <button id="map-zoom-out" class="px-2 py-1 ovnex-card text-xs hover:bg-[#21262d] transition" title="Uzaklaştır">-</button>
    <button id="map-reset" class="px-2 py-1 ovnex-card text-xs hover:bg-[#21262d] transition" title="Şanlıurfa'ya Dön">⌂</button>
    <select id="map-base-layer" class="ovnex-card px-2 py-1 text-xs bg-[#161b22] text-gray-300 border-0 outline-none">
        <option value="dark">Karanlık</option>
        <option value="light">Aydınlık</option>
        <option value="satellite">Uydu</option>
    </select>
</div>
