{{-- OVNEX Deprem Uyarı Banner'ı — 4.0+ depremlerde görünür --}}
<div id="earthquake-alert" class="hidden fixed top-12 left-1/2 -translate-x-1/2 z-[1000] bg-[#f85149] text-white px-6 py-3 rounded-b-lg shadow-lg text-sm font-semibold animate-pulse">
    <span id="earthquake-alert-msg">⚠ DEPREM UYARISI</span>
    <button onclick="document.getElementById('earthquake-alert').classList.add('hidden')" class="ml-4 text-white/70 hover:text-white">✕</button>
</div>

@push('scripts')
<script>
    if (typeof Echo !== 'undefined') {
        Echo.channel('earthquake.alerts')
            .listen('.earthquake.detected', (data) => {
                const eq = data.earthquake;
                const msg = `⚠ DEPREM! ${eq.magnitude} M - ${eq.location_name || 'Bilinmiyor'}`;
                document.getElementById('earthquake-alert-msg').textContent = msg;
                document.getElementById('earthquake-alert').classList.remove('hidden');
                setTimeout(() => {
                    document.getElementById('earthquake-alert').classList.add('hidden');
                }, 15000);
            });
    }
</script>
@endpush
