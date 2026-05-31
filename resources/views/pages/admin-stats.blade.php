@extends('layouts.app')

@section('title', 'OVNEX — Sistem Durumu')

@section('content')
<div class="p-4 space-y-4 max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-2">
        <span class="text-xs text-gray-500">@auth {{ auth()->user()->name }} ({{ auth()->user()->email }}) @endauth</span>
        <a href="{{ route('profile.edit') }}" class="text-xs ovnex-cyan hover:underline">Profil</a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-xs text-gray-500 hover:text-red-400">Çıkış Yap</button>
        </form>
    </div>
    <div class="ovnex-card p-4">
        <h2 class="text-lg font-bold ovnex-cyan mb-4">SİSTEM DURUMU</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="admin-stats">
            <div class="ovnex-card p-3 text-center">
                <div class="text-2xl font-bold ovnex-cyan" id="stat-ac">--</div>
                <div class="text-xs text-gray-500">Aktif Uçak</div>
            </div>
            <div class="ovnex-card p-3 text-center">
                <div class="text-2xl font-bold ovnex-orange" id="stat-eq">--</div>
                <div class="text-xs text-gray-500">Son Deprem</div>
            </div>
            <div class="ovnex-card p-3 text-center">
                <div class="text-2xl font-bold ovnex-green" id="stat-wth">--°C</div>
                <div class="text-xs text-gray-500">Şanlıurfa</div>
            </div>
            <div class="ovnex-card p-3 text-center">
                <div class="text-2xl font-bold ovnex-red" id="stat-tr">--</div>
                <div class="text-xs text-gray-500">Trafik Olayı</div>
            </div>
        </div>
    </div>

    <div class="ovnex-card p-4">
        <h3 class="text-sm font-bold ovnex-cyan mb-3">SON SİSTEM LOGLARI</h3>
        <div class="overflow-x-auto text-xs" id="log-table">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-500 border-b border-[#30363d]">
                        <th class="pb-2 pr-4">Servis</th>
                        <th class="pb-2 pr-4">İşlem</th>
                        <th class="pb-2 pr-4">Durum</th>
                        <th class="pb-2 pr-4">Kayıt</th>
                        <th class="pb-2 pr-4">Süre</th>
                        <th class="pb-2">Tarih</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sonLoglar as $log)
                    <tr class="border-b border-[#21262d] hover:bg-[#1c2128]">
                        <td class="py-2 pr-4 ovnex-cyan">{{ $log->service }}</td>
                        <td class="py-2 pr-4">{{ $log->action }}</td>
                        <td class="py-2 pr-4">
                            <span class="{{ $log->status === 'success' ? 'ovnex-green' : ($log->status === 'failed' ? 'ovnex-red' : 'ovnex-orange') }}">
                                {{ $log->status }}
                            </span>
                        </td>
                        <td class="py-2 pr-4">{{ $log->records_inserted ?? $log->records_fetched ?? 0 }}</td>
                        <td class="py-2 pr-4">{{ $log->duration_ms ? $log->duration_ms . ' ms' : '--' }}</td>
                        <td class="py-2 text-gray-500">{{ $log->created_at ? \Carbon\Carbon::parse($log->created_at)->format('H:i:s d.m.Y') : '--' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-4 text-center text-gray-500">Henüz sistem logu bulunmuyor.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/api/stats').then(r => r.json()).then(d => {
            document.getElementById('stat-ac').textContent = d.active_aircraft ?? 0;
            document.getElementById('stat-eq').textContent = (d.last_earthquake ?? '--') + ' M';
            document.getElementById('stat-wth').textContent = (d.weather_temp ?? '--') + '\u00B0C';
            document.getElementById('stat-tr').textContent = d.active_traffic ?? 0;
        }).catch(() => {});
    });
</script>
@endpush
