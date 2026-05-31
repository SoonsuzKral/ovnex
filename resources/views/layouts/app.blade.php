<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OVNEX — Osman & Vildan Intelligence Nexus')</title>
    <meta name="description" content="Türkiye'nin Gerçek Zamanlı OSINT Harita Portalı">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <style>
        * { font-family: 'JetBrains Mono', 'Space Mono', monospace; }
        body { background: #0d1117; color: #c9d1d9; overflow: hidden; height: 100vh; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0d1117; }
        ::-webkit-scrollbar-thumb { background: #30363d; border-radius: 3px; }
        .ovnex-card { background: #161b22; border: 1px solid #30363d; border-radius: 8px; }
        .ovnex-header { background: #161b22; border-bottom: 1px solid #30363d; height: 48px; }
        .ovnex-footer { background: #161b22; border-top: 1px solid #30363d; height: 36px; }
        .ovnex-cyan { color: #00d4ff; }
        .ovnex-orange { color: #ff6b35; }
        .ovnex-green { color: #3fb950; }
        .ovnex-red { color: #f85149; }
        .btn-cyan { background: #00d4ff; color: #0d1117; font-weight: 600; }
        .btn-cyan:hover { background: #00b8d4; }
        #map { height: calc(100vh - 84px); width: 100%; z-index: 1; }
        .leaflet-container { background: #0d1117 !important; }
        .sidebar-panel { width: 280px; min-width: 280px; height: calc(100vh - 84px); overflow-y: auto; background: #161b22; border-color: #30363d; }
    </style>

    @stack('head')
</head>
<body>
    <div class="flex flex-col h-screen">
        {{-- HEADER --}}
        <header class="ovnex-header flex items-center justify-between px-4 z-50 relative">
            <div class="flex items-center gap-3">
                <span class="text-lg font-bold ovnex-cyan tracking-wider">OVNEX</span>
                <span class="text-xs text-gray-500 hidden sm:inline">OSMAN & VILDAN INTELLIGENCE NEXUS</span>
            </div>
            <div class="flex items-center gap-4 text-xs">
                @include('components.map-controls')
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-400" x-data="{ now: new Date().toLocaleString('tr-TR') }" x-init="setInterval(() => now = new Date().toLocaleString('tr-TR'), 1000)">
                <span x-text="now"></span>
            </div>
        </header>

        {{-- AD LEADERBOARD --}}
        @include('components.ad-leaderboard')

        {{-- MAIN CONTENT --}}
        <div class="flex flex-1 overflow-hidden">
            {{-- LEFT SIDEBAR --}}
            <aside class="sidebar-panel border-r border-[#30363d] hidden lg:flex flex-col">
                @include('components.sidebar-left')
            </aside>

            {{-- MAP --}}
            <main class="flex-1 relative">
                <div id="map"></div>
                @yield('content')
                @include('components.earthquake-alert-banner')
                {{-- INTERSTITIAL AD --}}
                @include('components.ad-interstitial')
            </main>

            {{-- RIGHT SIDEBAR --}}
            <aside class="sidebar-panel border-l border-[#30363d] hidden lg:flex flex-col">
                @include('components.sidebar-right')
            </aside>
        </div>

        {{-- FOOTER --}}
        <footer class="ovnex-footer flex items-center justify-between px-4 text-xs text-gray-500 z-50 relative">
            <div class="flex gap-4">
                <span>Aktif Uçak: <strong id="stat-aircraft" class="ovnex-cyan">0</strong></span>
                <span>Son Deprem: <strong id="stat-earthquake" class="ovnex-orange">--</strong></span>
                <span>Sıcaklık: <strong id="stat-temp" class="ovnex-green">--°C</strong></span>
                <span>Trafik: <strong id="stat-traffic" class="ovnex-red">0</strong></span>
            </div>
            <div>OVNEX © {{ date('Y') }} — Osman & Vildan Projesi</div>
        </footer>
    </div>

    {{-- MOBILE STICKY AD --}}
    @include('components.ad-mobile-footer')

    @stack('scripts')

    <script src="{{ asset('js/websocket.js') }}"></script>
    <script src="{{ asset('js/map.js') }}"></script>
</body>
</html>
