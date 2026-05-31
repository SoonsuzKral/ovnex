/**
 * OVNEX — Ana Harita Başlatıcı
 * Leaflet.js ile dark tema harita, tüm katmanları yönetir
 */

document.addEventListener('DOMContentLoaded', function () {
    if (typeof L === 'undefined') return;

    // Şanlıurfa merkez
    const MAP_CENTER = [37.1591, 38.7969];
    const MAP_ZOOM = 12;
    const TURKEY_ZOOM = 6;

    let map = L.map('map', {
        center: MAP_CENTER,
        zoom: MAP_ZOOM,
        zoomControl: false,
        attributionControl: false,
    });

    // Dark tile layer (CartoDB Dark Matter)
    let darkTile = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 19,
        subdomains: 'abcd',
    }).addTo(map);

    let lightTile = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 19,
        subdomains: 'abcd',
    });

    let satelliteTile = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19,
    });

    // Base layer kontrolü
    let baseLayers = {
        'Karanlık': darkTile,
        'Aydınlık': lightTile,
        'Uydu': satelliteTile,
    };

    L.control.layers(baseLayers, null, { position: 'bottomleft' }).addTo(map);

    // Zoom kontrolü
    document.getElementById('map-zoom-in')?.addEventListener('click', () => map.zoomIn());
    document.getElementById('map-zoom-out')?.addEventListener('click', () => map.zoomOut());
    document.getElementById('map-reset')?.addEventListener('click', () => map.setView(MAP_CENTER, MAP_ZOOM));

    document.getElementById('map-base-layer')?.addEventListener('change', function (e) {
        const val = e.target.value;
        darkTile.remove();
        lightTile.remove();
        satelliteTile.remove();
        if (val === 'dark') darkTile.addTo(map);
        else if (val === 'light') lightTile.addTo(map);
        else if (val === 'satellite') satelliteTile.addTo(map);
    });

    // Layer toggles
    let layers = {};

    function initLayerToggle(id, layerObj) {
        const cb = document.getElementById(id);
        if (!cb || !layerObj) return;
        layers[id] = layerObj;
        cb.addEventListener('change', function () {
            if (this.checked) {
                if (!map.hasLayer(layerObj)) layerObj.addTo(map);
            } else {
                if (map.hasLayer(layerObj)) map.removeLayer(layerObj);
            }
        });
    }

    // Katmanları yükle
    let acLayer = L.layerGroup();
    let eqLayer = L.layerGroup();
    let trLayer = L.layerGroup();
    let weLayer = L.layerGroup();
    let vsLayer = L.layerGroup();

    acLayer.addTo(map);
    eqLayer.addTo(map);

    initLayerToggle('layer-aircraft', acLayer);
    initLayerToggle('layer-earthquakes', eqLayer);
    initLayerToggle('layer-traffic', trLayer);
    initLayerToggle('layer-weather', weLayer);
    initLayerToggle('layer-vessels', vsLayer);

    // Uçak ikonları için özel Leaflet divIcon
    const aircraftIcon = L.divIcon({
        className: 'aircraft-marker',
        html: '<svg viewBox="0 0 24 24" width="16" height="16" fill="#00d4ff"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>',
        iconSize: [16, 16],
        iconAnchor: [8, 8],
    });

    const earthquakeIcon = L.divIcon({
        className: 'earthquake-marker',
        html: '<svg viewBox="0 0 24 24" width="20" height="20" fill="#ff6b35"><circle cx="12" cy="12" r="6" opacity="0.4"/><circle cx="12" cy="12" r="3"/></svg>',
        iconSize: [20, 20],
        iconAnchor: [10, 10],
    });

    // Global erişim
    window.ovnexMap = map;
    window.ovnexLayers = { acLayer, eqLayer, trLayer, weLayer, vsLayer };
    window.ovnexIcons = { aircraftIcon, earthquakeIcon };

    // İlk veri yükleme
    loadAircraft();
    loadEarthquakes();
    loadTraffic();
    loadVessels();
    loadWeather();

    // Periyodik yenileme
    setInterval(loadAircraft, 10000);
    setInterval(loadEarthquakes, 60000);
    setInterval(loadTraffic, 120000);
    setInterval(loadVessels, 30000);
    setInterval(loadWeather, 900000);
});

function loadAircraft() {
    fetch('/api/aircraft')
        .then(r => r.json())
        .then(data => {
            const layer = window.ovnexLayers?.acLayer;
            if (!layer) return;
            layer.clearLayers();
            data.forEach(ac => {
                if (ac.latitude && ac.longitude) {
                    let heading = ac.heading || 0;
                    let icon = L.divIcon({
                        className: 'aircraft-marker',
                        html: `<svg viewBox="0 0 24 24" width="16" height="16" fill="#00d4ff" style="transform:rotate(${heading}deg)"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>`,
                        iconSize: [16, 16],
                        iconAnchor: [8, 8],
                    });
                    let marker = L.marker([ac.latitude, ac.longitude], { icon }).addTo(layer);
                    marker.bindPopup(`
                        <div style="font-family:'JetBrains Mono',monospace;background:#161b22;color:#c9d1d9;font-size:12px;">
                            <div style="font-weight:bold;color:#00d4ff">${ac.callsign || ac.icao24 || '---'}</div>
                            <div>İrtifa: ${ac.altitude_baro || '---'} m</div>
                            <div>Hız: ${ac.velocity ? Math.round(ac.velocity * 3.6) + ' km/h' : '---'}</div>
                            <div>Yön: ${heading}°</div>
                            <div style="font-size:10px;color:#666">${ac.origin_country || ''}</div>
                        </div>
                    `);
                }
            });
            document.getElementById('stat-aircraft').textContent = data.length;
        })
        .catch(() => {});
}

function loadEarthquakes() {
    fetch('/api/earthquakes?min_magnitude=1.0')
        .then(r => r.json())
        .then(data => {
            const layer = window.ovnexLayers?.eqLayer;
            if (!layer) return;
            layer.clearLayers();
            data.forEach(eq => {
                if (eq.latitude && eq.longitude) {
                    let mag = eq.magnitude || 1;
                    let size = Math.max(6, Math.min(30, mag * 6));
                    let color = mag >= 5 ? '#f85149' : mag >= 4 ? '#ff6b35' : mag >= 3 ? '#d29922' : '#3fb950';
                    let icon = L.divIcon({
                        className: 'earthquake-marker',
                        html: `<div style="width:${size}px;height:${size}px;border-radius:50%;background:${color};opacity:0.6;border:2px solid ${color};"></div>`,
                        iconSize: [size, size],
                        iconAnchor: [size / 2, size / 2],
                    });
                    let marker = L.marker([eq.latitude, eq.longitude], { icon }).addTo(layer);
                    marker.bindPopup(`
                        <div style="font-family:'JetBrains Mono',monospace;background:#161b22;color:#c9d1d9;font-size:12px;">
                            <div style="font-weight:bold;color:#ff6b35">${eq.magnitude} M</div>
                            <div>${eq.location_name || '---'}</div>
                            <div>Derinlik: ${eq.depth_km || '---'} km</div>
                            <div>${eq.occurred_at ? new Date(eq.occurred_at).toLocaleString('tr-TR') : '---'}</div>
                        </div>
                    `);
                }
            });
            document.getElementById('stat-earthquake').textContent = data.length ? data[0].magnitude + ' M' : '--';
        })
        .catch(() => {});
}

function loadTraffic() {
    fetch('/api/traffic')
        .then(r => r.json())
        .then(data => {
            const layer = window.ovnexLayers?.trLayer;
            if (!layer) return;
            layer.clearLayers();
            data.forEach(inc => {
                if (inc.start_lat && inc.start_lng) {
                    let colors = { 1: '#3fb950', 2: '#d29922', 3: '#ff6b35', 4: '#f85149' };
                    let color = colors[inc.severity] || '#ff6b35';
                    let marker = L.circleMarker([inc.start_lat, inc.start_lng], {
                        radius: 8,
                        color: color,
                        fillColor: color,
                        fillOpacity: 0.3,
                    }).addTo(layer);
                    marker.bindPopup(`<div style="font-family:'JetBrains Mono',monospace;background:#161b22;color:#c9d1d9;font-size:12px;">${inc.description || inc.incident_type}</div>`);
                }
            });
            document.getElementById('stat-traffic').textContent = data.length;
        })
        .catch(() => {});
}

function loadVessels() {
    fetch('/api/vessels')
        .then(r => r.json())
        .then(data => {
            const layer = window.ovnexLayers?.vsLayer;
            if (!layer) return;
            layer.clearLayers();
            data.forEach(v => {
                if (v.latitude && v.longitude) {
                    let icon = L.divIcon({
                        className: 'vessel-marker',
                        html: `<svg viewBox="0 0 24 24" width="14" height="14" fill="#00d4ff"><path d="M20 21c-1.39 0-2.78-.47-4-1.32-2.44 1.71-5.56 1.71-8 0C6.78 20.53 5.39 21 4 21H2v2h2c1.38 0 2.74-.35 4-.99 2.52 1.29 5.48 1.29 8 0 1.26.65 2.62.99 4 .99h2v-2h-2zM3.95 19H4c1.6 0 3.02-.88 4-2 .98 1.12 2.4 2 4 2s3.02-.88 4-2c.98 1.12 2.4 2 4 2h.05l1.89-6.68c.08-.26.06-.54-.06-.78s-.34-.42-.6-.5L20 10.62V6c0-1.1-.9-2-2-2h-3V1H9v3H6c-1.1 0-2 .9-2 2v4.62l-1.29.42c-.26.08-.48.26-.6.5s-.15.52-.06.78L3.95 19z"/></svg>`,
                        iconSize: [14, 14],
                        iconAnchor: [7, 7],
                    });
                    let marker = L.marker([v.latitude, v.longitude], { icon }).addTo(layer);
                    marker.bindPopup(`<div style="font-family:'JetBrains Mono',monospace;background:#161b22;color:#c9d1d9;font-size:12px;">${v.vessel_name || v.mmsi || 'Gemi'}</div>`);
                }
            });
            document.getElementById('stat-active-vessel').textContent = data.length;
        })
        .catch(() => {});
}

function loadWeather() {
    fetch('/api/weather/current')
        .then(r => r.json())
        .then(data => {
            if (data.temperature_c) {
                document.getElementById('stat-temp').textContent = data.temperature_c + '°C';
            }
        })
        .catch(() => {});
}
