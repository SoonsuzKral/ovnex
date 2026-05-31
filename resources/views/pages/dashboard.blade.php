@extends('layouts.app')

@section('title', 'OVNEX — Dashboard')

@section('content')
    {{-- Harita burada --}}
@endsection

@push('scripts')
<script>
    // İstatistik verilerini footer'a yaz
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/api/stats')
            .then(res => res.json())
            .then(data => {
                document.getElementById('stat-aircraft').textContent = data.active_aircraft ?? 0;
                document.getElementById('stat-earthquake').textContent = data.last_earthquake ?? '--';
                document.getElementById('stat-temp').textContent = (data.weather_temp ?? '--') + '°C';
                document.getElementById('stat-traffic').textContent = data.active_traffic ?? 0;
            })
            .catch(() => {});
    });
</script>
@endpush
