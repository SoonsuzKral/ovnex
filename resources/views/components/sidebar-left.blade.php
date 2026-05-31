{{-- OVNEX Sol Panel — Katman kontrolü, istatistikler ve filtreler --}}
<div class="p-3 space-y-4 text-xs">
    <div class="ovnex-card p-3">
        <h3 class="text-sm font-semibold ovnex-cyan mb-2 tracking-wider">KATMANLAR</h3>
        <div class="space-y-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" id="layer-aircraft" checked class="accent-[#00d4ff]">
                <span>✈ Uçak Takibi</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" id="layer-earthquakes" checked class="accent-[#ff6b35]">
                <span>🔴 Depremler</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" id="layer-traffic" class="accent-[#f85149]">
                <span>🚗 Trafik</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" id="layer-weather" class="accent-[#3fb950]">
                <span>🌤 Hava Durumu</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" id="layer-vessels" class="accent-[#00d4ff]">
                <span>🚢 Gemiler</span>
            </label>
        </div>
    </div>

    <div class="ovnex-card p-3">
        <h3 class="text-sm font-semibold ovnex-cyan mb-2 tracking-wider">İSTATİSTİK</h3>
        <div class="space-y-1 text-gray-400" id="stats-panel">
            <div class="flex justify-between"><span>Toplam Uçak:</span><span id="stat-total-ac" class="ovnex-cyan">--</span></div>
            <div class="flex justify-between"><span>Son Deprem:</span><span id="stat-last-eq" class="ovnex-orange">--</span></div>
            <div class="flex justify-between"><span>Aktif Gemi:</span><span id="stat-active-vessel" class="ovnex-cyan">--</span></div>
            <div class="flex justify-between"><span>Bugünkü Haber:</span><span id="stat-today-news" class="ovnex-green">--</span></div>
        </div>
    </div>

    <div class="ovnex-card p-3">
        @include('components.ad-rectangle')
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/api/stats')
            .then(r => r.json())
            .then(d => {
                document.getElementById('stat-total-ac').textContent = d.active_aircraft ?? 0;
                document.getElementById('stat-last-eq').textContent = d.last_earthquake ? d.last_earthquake + ' M' : '--';
                document.getElementById('stat-active-vessel').textContent = d.active_vessels ?? 0;
                document.getElementById('stat-today-news').textContent = d.total_news ?? 0;
            })
            .catch(() => {});
    });
</script>
