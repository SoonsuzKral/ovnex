# OVNEX — Google Jules için Proje Üretim Talimatları

## GÖREV

Sen OVNEX adlı bir Laravel 11 projesinin tüm kodunu üreteceksin.
OVNEX, Türkiye'de gerçek zamanlı OSINT tabanlı bir harita ve istihbarat portalıdır.
Şanlıurfa özelinde trafik, uçak, deprem, hava durumu ve haber verilerini tek haritada gösterir.

Senden istenen: aşağıdaki spesifikasyona göre eksiksiz çalışan Laravel 11 kodu üretmen.

---

## TEKNOLOJİ STACK

- PHP 8.3 + Laravel 11
- PostgreSQL 16 + PostGIS (coğrafi sorgular)
- Redis 7 (cache + queue)
- Laravel Reverb (WebSocket — gerçek zamanlı güncelleme)
- Laravel Horizon (queue yönetim paneli)
- Blade + Alpine.js + Livewire 3
- Leaflet.js 1.9 (harita)
- Chart.js / ApexCharts (grafik)
- D3.js (entity ilişki grafiği)
- Tailwind CSS 3

---

## VERİTABANI

Aşağıdaki tabloları oluştur:

### aircraft_positions
```sql
- id BIGSERIAL PK
- icao24 VARCHAR(10) NOT NULL
- callsign VARCHAR(20)
- origin_country VARCHAR(50)
- latitude DECIMAL(10,7)
- longitude DECIMAL(10,7)
- altitude_baro DECIMAL(10,2)  -- metre
- altitude_geo DECIMAL(10,2)
- velocity DECIMAL(8,2)        -- m/s
- heading DECIMAL(5,2)         -- derece
- vertical_rate DECIMAL(8,2)
- on_ground BOOLEAN DEFAULT false
- squawk VARCHAR(10)
- departure_airport VARCHAR(10)
- arrival_airport VARCHAR(10)
- aircraft_type VARCHAR(20)
- geom GEOMETRY(POINT,4326)    -- PostGIS
- recorded_at TIMESTAMPTZ NOT NULL
```

### earthquakes
```sql
- id BIGSERIAL PK
- external_id VARCHAR(100) UNIQUE
- source VARCHAR(20) DEFAULT 'AFAD'
- latitude DECIMAL(10,7) NOT NULL
- longitude DECIMAL(10,7) NOT NULL
- depth_km DECIMAL(8,2)
- magnitude DECIMAL(4,2) NOT NULL
- magnitude_type VARCHAR(10)
- location_name VARCHAR(200)
- province VARCHAR(100)
- district VARCHAR(100)
- geom GEOMETRY(POINT,4326)
- occurred_at TIMESTAMPTZ NOT NULL
- created_at TIMESTAMPTZ DEFAULT NOW()
```

### weather_snapshots
```sql
- id BIGSERIAL PK
- city VARCHAR(100) NOT NULL
- latitude DECIMAL(10,7)
- longitude DECIMAL(10,7)
- temperature_c DECIMAL(5,2)
- feels_like_c DECIMAL(5,2)
- humidity_pct SMALLINT
- wind_speed_ms DECIMAL(6,2)
- wind_direction SMALLINT
- condition_code VARCHAR(20)
- condition_text VARCHAR(100)
- condition_icon VARCHAR(200)
- visibility_km DECIMAL(6,2)
- pressure_hpa DECIMAL(7,2)
- rainfall_mm DECIMAL(6,2) DEFAULT 0
- recorded_at TIMESTAMPTZ NOT NULL
```

### news_feed
```sql
- id BIGSERIAL PK
- external_url TEXT UNIQUE
- source_name VARCHAR(100) NOT NULL
- source_type VARCHAR(20)
- title TEXT NOT NULL
- summary TEXT
- category VARCHAR(50)  -- earthquake, fire, traffic, flood, conflict, emergency, general, weather
- severity VARCHAR(20)  -- low, medium, high, critical
- latitude DECIMAL(10,7)
- longitude DECIMAL(10,7)
- province VARCHAR(100)
- image_url TEXT
- published_at TIMESTAMPTZ NOT NULL
- created_at TIMESTAMPTZ DEFAULT NOW()
- is_verified BOOLEAN DEFAULT false
- geom GEOMETRY(POINT,4326)
```

### traffic_incidents
```sql
- id BIGSERIAL PK
- external_id VARCHAR(100) UNIQUE
- incident_type VARCHAR(50)  -- accident, jam, roadwork, closure, hazard
- severity SMALLINT  -- 1-4
- description TEXT
- road_name VARCHAR(200)
- start_lat DECIMAL(10,7)
- start_lng DECIMAL(10,7)
- end_lat DECIMAL(10,7)
- end_lng DECIMAL(10,7)
- delay_seconds INTEGER DEFAULT 0
- province VARCHAR(100)
- geom GEOMETRY(LINESTRING,4326)
- started_at TIMESTAMPTZ
- ended_at TIMESTAMPTZ
- created_at TIMESTAMPTZ DEFAULT NOW()
```

### vessel_positions
```sql
- id BIGSERIAL PK
- mmsi VARCHAR(15)
- vessel_name VARCHAR(100)
- vessel_type VARCHAR(50)
- flag VARCHAR(5)
- latitude DECIMAL(10,7)
- longitude DECIMAL(10,7)
- speed_knots DECIMAL(6,2)
- heading SMALLINT
- destination VARCHAR(200)
- eta TIMESTAMPTZ
- status VARCHAR(50)
- geom GEOMETRY(POINT,4326)
- recorded_at TIMESTAMPTZ NOT NULL
```

### camera_sources
```sql
- id BIGSERIAL PK
- name VARCHAR(200) NOT NULL
- location_description TEXT
- latitude DECIMAL(10,7) NOT NULL
- longitude DECIMAL(10,7) NOT NULL
- stream_url TEXT
- thumbnail_url TEXT
- is_active BOOLEAN DEFAULT true
- province VARCHAR(100) DEFAULT 'Şanlıurfa'
- district VARCHAR(100)
- geom GEOMETRY(POINT,4326)
- created_at TIMESTAMPTZ DEFAULT NOW()
- updated_at TIMESTAMPTZ
```

### ad_impressions
```sql
- id BIGSERIAL PK
- ad_unit VARCHAR(50) NOT NULL
- ad_type VARCHAR(20)
- impressions INTEGER DEFAULT 0
- clicks INTEGER DEFAULT 0
- ip_hash VARCHAR(64)
- country VARCHAR(5)
- recorded_at DATE NOT NULL
```

### system_logs
```sql
- id BIGSERIAL PK
- service VARCHAR(50) NOT NULL
- action VARCHAR(50)
- status VARCHAR(20)
- records_fetched INTEGER DEFAULT 0
- records_inserted INTEGER DEFAULT 0
- duration_ms INTEGER
- error_message TEXT
- created_at TIMESTAMPTZ DEFAULT NOW()
```

---

## KLASÖR YAPISI

```
app/
├── Console/
│   └── Commands/
│       └── CollectAllDataCommand.php
├── Events/
│   ├── AircraftUpdated.php
│   ├── EarthquakeDetected.php
│   └── NewsReceived.php
├── Http/
│   └── Controllers/
│       ├── MapController.php
│       ├── AircraftController.php
│       ├── EarthquakeController.php
│       ├── WeatherController.php
│       ├── NewsController.php
│       ├── TrafficController.php
│       └── VesselController.php
├── Jobs/
│   ├── CollectAircraftDataJob.php
│   ├── CollectEarthquakeDataJob.php
│   ├── CollectWeatherDataJob.php
│   ├── CollectNewsDataJob.php
│   ├── CollectTrafficDataJob.php
│   └── CollectVesselDataJob.php
├── Models/
│   ├── AircraftPosition.php
│   ├── Earthquake.php
│   ├── WeatherSnapshot.php
│   ├── NewsFeed.php
│   ├── TrafficIncident.php
│   ├── VesselPosition.php
│   ├── CameraSource.php
│   ├── AdImpression.php
│   └── SystemLog.php
└── Services/
    ├── OpenSkyService.php
    ├── AfadEarthquakeService.php
    ├── OpenWeatherService.php
    ├── RssNewsService.php
    ├── TomTomTrafficService.php
    ├── MarineTrafficService.php
    └── GeocodingService.php

resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php
│   ├── components/
│   │   ├── sidebar-panel.blade.php
│   │   ├── map-controls.blade.php
│   │   ├── earthquake-alert.blade.php
│   │   ├── news-feed.blade.php
│   │   ├── ad-leaderboard.blade.php
│   │   ├── ad-rectangle.blade.php
│   │   └── weather-widget.blade.php
│   └── pages/
│       └── dashboard.blade.php
└── js/
    ├── map.js
    ├── aircraft-layer.js
    ├── earthquake-layer.js
    ├── traffic-layer.js
    ├── vessel-layer.js
    ├── weather-layer.js
    └── websocket.js

routes/
├── web.php
└── api.php
```

---

## SERVİS DETAYLARI

### OpenSkyService.php
```
- Endpoint: https://opensky-network.org/api/states/all
- Parametreler: lamin=35.5, lomin=25.0, lamax=42.1, lomax=44.8 (Türkiye bbox)
- Auth: Basic HTTP (OPENSKY_USERNAME + OPENSKY_PASSWORD env)
- Response parse: [icao24, callsign, origin_country, time_position, last_contact, longitude, latitude, baro_altitude, on_ground, velocity, true_track, vertical_rate, sensors, geo_altitude, squawk, spi, position_source]
- Her 10 saniyede bir tetiklenir
- Sonuçları aircraft_positions tablosuna yaz
- broadcast(new AircraftUpdated($positions)) çağır
```

### AfadEarthquakeService.php
```
- Endpoint: https://deprem.afad.gov.tr/apiv2/event/filter
- Parametreler: start={son_1_saat}, end={şimdi}, orderby=timedesc, limit=100
- Her 1 dakikada bir tetiklenir
- external_id ile duplicate kontrolü yap
- magnitude >= 4.0 ise broadcast(new EarthquakeDetected($eq)) tetikle
- Kandilli RSS yedek: https://www.koeri.boun.edu.tr/scripts/lst0.asp
```

### OpenWeatherService.php
```
- Current: https://api.openweathermap.org/data/2.5/weather?lat=37.1591&lon=38.7969&appid={key}&units=metric&lang=tr
- Şanlıurfa koordinatları sabit
- Her 15 dakikada güncelle
- Tüm Türkiye büyük şehirleri için de çalıştır (Istanbul, Ankara, Izmir, Gaziantep)
```

### RssNewsService.php
```
RSS Kaynakları (hepsini parse et):
- https://www.aa.com.tr/tr/rss/default?cat=guncel
- https://www.trthaber.com/sondakika.rss
- https://www.hurriyet.com.tr/rss/anasayfa
- https://www.sabah.com.tr/rss/anasayfa.xml
- Şanlıurfa yerel: https://www.sanliurfa.com.tr/rss  (varsa)

Her haber için:
- Kategori tespiti: title/description'da anahtar kelime eşleşmesi
  earthquake: ["deprem", "sarsıntı", "richter"]
  fire: ["yangın", "yanıyor", "alev"]
  traffic: ["kaza", "trafik", "çarpışma", "yığılma"]
  flood: ["sel", "taşkın", "su baskını"]
  conflict: ["çatışma", "operasyon", "bomba", "saldırı"]
  emergency: ["ohal", "afet", "acil", "alarm"]
- Province tespiti: title/description'da il adı ara
- Her 5 dakikada çalıştır
```

### TomTomTrafficService.php
```
- Flow Endpoint: https://api.tomtom.com/traffic/services/4/flowSegmentData/absolute/10/json
- Incidents: https://api.tomtom.com/traffic/services/5/incidentDetails
- Bbox: Şanlıurfa için 36.8,38.2,37.5,39.4
- Her 2 dakikada çalıştır
```

---

## CONTROLLER DETAYLARI

### MapController.php
- `index()`: Ana dashboard sayfasını döndür
- `getInitialData()`: JSON — tüm aktif veri katmanları (son 1 saat uçak, son 24 saat deprem, anlık hava)

### AircraftController.php
- `index()`: JSON liste — aktif uçaklar (son 60 saniye)
- `show($icao24)`: Tek uçak detayı

### EarthquakeController.php
- `index()`: JSON liste — son 24 saat depremler, ?min_magnitude filtresi
- `recent()`: Son 10 deprem

### NewsController.php
- `index()`: JSON liste — son 50 haber, ?category filtresi, ?province filtresi
- `latest()`: Son 10 haber (sidebar için)

---

## WEBSOCKET EVENTS

### AircraftUpdated
- Channel: `aircraft.live`
- Data: tüm aktif uçak pozisyonları dizisi

### EarthquakeDetected
- Channel: `earthquake.alerts`
- Data: deprem nesnesi (magnitude, location, time)

### NewsReceived
- Channel: `news.live`
- Data: yeni haber nesnesi

---

## SCHEDULER (Kernel.php veya routes/console.php)

```php
Schedule::job(new CollectAircraftDataJob)->everyTenSeconds();
Schedule::job(new CollectEarthquakeDataJob)->everyMinute();
Schedule::job(new CollectWeatherDataJob)->everyFifteenMinutes();
Schedule::job(new CollectNewsDataJob)->everyFiveMinutes();
Schedule::job(new CollectTrafficDataJob)->everyTwoMinutes();
Schedule::job(new CollectVesselDataJob)->everyThirtySeconds();
```

---

## FRONTEND DETAYLARI

### app.blade.php (Layout)
- Tailwind CSS CDN
- Leaflet.js CDN
- Alpine.js CDN
- Laravel Echo + Pusher.js CDN
- Dark tema (#0d1117 arkaplan)
- Sol sidebar: 320px sabit
- Merkez: Harita (flex-1)
- Sağ sidebar: 280px (haberler + uyarılar)
- Üst bar: Logo OVNEX + katman kontrolleri + saat

### dashboard.blade.php
- Harita div: `<div id="map" style="width:100%;height:100%"></div>`
- Katmanlar: Uçak, Trafik, Deprem, Hava, Gemi, Kamera toggle butonları
- Alt bar: Anlık istatistikler (aktif uçak sayısı, son deprem, sıcaklık)

### Harita Başlangıç
```javascript
const map = L.map('map', {
    center: [37.1591, 38.7969], // Şanlıurfa
    zoom: 11,
    zoomControl: true
});

// Dark tile layer
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '©OpenStreetMap ©CartoDB',
    maxZoom: 19
}).addTo(map);
```

### Uçak İkonları
```javascript
// Özel uçak ikonu (yön göstergeli)
function createAircraftIcon(heading) {
    return L.divIcon({
        html: `<div style="transform:rotate(${heading}deg)">✈</div>`,
        className: 'aircraft-icon'
    });
}
```

---

## REKLAM BİRİMLERİ

### Yerleşim Noktaları
1. `components/ad-leaderboard.blade.php`: Header altı 728x90
2. `components/ad-rectangle.blade.php`: Sağ sidebar 300x250
3. Haber listesi içi native: Her 5 haberden sonra 300x250
4. Mobil footer sticky: 320x50

### AdSense Kodu Şablonu
```html
<!-- Leaderboard -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-XXXXXXXXXXXXXXXX"
     data-ad-slot="XXXXXXXXXX"></ins>
<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
```

---

## .ENV GEREKSİNİMLERİ

```env
OPENSKY_USERNAME=
OPENSKY_PASSWORD=
OPENWEATHER_API_KEY=
TOMTOM_API_KEY=
AFAD_API_URL=https://deprem.afad.gov.tr/apiv2/event/filter
MARINE_TRAFFIC_API_KEY=
REVERB_APP_ID=ovnex
REVERB_APP_KEY=ovnex_key
REVERB_APP_SECRET=
REVERB_HOST=0.0.0.0
REVERB_PORT=8080
ADSENSE_PUBLISHER_ID=ca-pub-XXXXXXXXXXXXXXXX
```

---

## ÜRETMENİ İSTEDİKLERİM

1. Tüm migration dosyaları (9 tablo)
2. Tüm Model dosyaları ($fillable, $casts, scope'lar)
3. Tüm Service dosyaları (HTTP client ile API çağrısı)
4. Tüm Job dosyaları (queue, hata yönetimi, SystemLog kaydı)
5. Tüm Controller dosyaları (JSON response)
6. Event dosyaları (ShouldBroadcast)
7. Routes (web.php + api.php)
8. Blade view'lar (layout + dashboard + components)
9. JavaScript dosyaları (harita katmanları)
10. Scheduler kaydı

**Lütfen her dosyayı eksiksiz, çalışır hâlde üret. Açıklama satırları Türkçe olsun.**

---

*OVNEX — Osman & Vildan Intelligence Nexus | Laravel 11 Projesi*
