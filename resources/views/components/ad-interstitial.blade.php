{{-- OVNEX Interstitial Reklam (Tam Ekran Geçiş) — İlk yüklemede 3sn göster --}}
<div id="ad-interstitial" class="hidden fixed inset-0 z-[99999] bg-black/90 flex items-center justify-center" x-data="{ show: false }" x-init="setTimeout(() => show = true, 2000); setTimeout(() => show = false, 5000)">
    <div x-show="show" x-transition class="relative bg-[#161b22] border border-[#30363d] rounded-lg p-6 text-center max-w-sm mx-4">
        <div class="ad-container" style="width: 300px; height: 250px; background: #0d1117; display: flex; align-items: center; justify-content: center; border-radius: 4px; margin: 0 auto;">
            <span class="text-[10px] text-gray-600">— REKLAM GEÇİŞİ —</span>
            {{--
            ADSENSE INTERSTITIAL:
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <ins class="adsbygoogle" style="display:block"
                 data-ad-client="{{ env('ADSENSE_PUBLISHER_ID') }}"
                 data-ad-format="interstitial"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            --}}
        </div>
        <button @click="show = false" class="mt-3 px-4 py-2 btn-cyan text-xs rounded transition">REKLAMI GEÇ</button>
    </div>
</div>
