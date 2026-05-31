{{-- OVNEX Sağ Panel — Haber akışı, uyarılar ve reklam --}}
<div class="p-3 space-y-4 text-xs">
    <div class="ovnex-card p-3">
        @include('components.ad-rectangle')
    </div>

    <div class="ovnex-card p-3">
        <h3 class="text-sm font-semibold ovnex-cyan mb-2 tracking-wider">SON HABERLER</h3>
        <div class="space-y-2 max-h-96 overflow-y-auto" id="news-feed">
            @forelse ($sonHaberler ?? [] as $haber)
                @include('components.news-feed-item', ['haber' => $haber])
            @empty
                <p class="text-gray-500 text-xs">Henüz haber bulunmuyor.</p>
            @endforelse
        </div>
    </div>

    <div class="ovnex-card p-3">
        @include('components.weather-widget')
    </div>
</div>
