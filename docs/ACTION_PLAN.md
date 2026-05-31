# OVNEX — ACTION_PLAN.md

## Eylem Planı — Adım Adım Başlangıç Rehberi

---

## GÜN 1-3 — Ortam Kurulumu

### 1. Sunucu Hazırlığı
```bash
# Ubuntu 22.04 VPS (DigitalOcean / Hetzner / Contabo)
apt update && apt upgrade -y
apt install -y nginx postgresql postgresql-contrib redis-server php8.3 php8.3-fpm
apt install -y php8.3-cli php8.3-pgsql php8.3-redis php8.3-xml php8.3-curl php8.3-zip
apt install -y composer nodejs npm git supervisor

# PostGIS (coğrafi sorgular için)
apt install -y postgis postgresql-16-postgis-3
```

### 2. Laravel Projesi Oluştur
```bash
composer create-project laravel/laravel ovnex
cd ovnex
composer require laravel/reverb          # WebSocket
composer require laravel/horizon         # Queue yönetimi
composer require predis/predis           # Redis client
composer require spatie/laravel-tags     # Etiketleme
```

### 3. Veritabanı Kur
```bash
psql -U postgres
CREATE DATABASE ovnex;
CREATE USER ovnex_user WITH PASSWORD 'güçlü_şifre_yaz';
GRANT ALL PRIVILEGES ON DATABASE ovnex TO ovnex_user;
\c ovnex
CREATE EXTENSION postgis;
```

### 4. .env Yapılandır
```env
APP_NAME=OVNEX
APP_ENV=production
APP_URL=https://ovnex.com.tr

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ovnex
DB_USERNAME=ovnex_user
DB_PASSWORD=güçlü_şifre_yaz

REDIS_HOST=127.0.0.1
REDIS_PORT=6379

OPENSKY_USERNAME=            # opensky-network.org ücretsiz kayıt
OPENSKY_PASSWORD=
OPENWEATHER_API_KEY=         # openweathermap.org ücretsiz kayıt
TOMTOM_API_KEY=              # developer.tomtom.com ücretsiz kayıt
AFAD_API_URL=https://deprem.afad.gov.tr/apiv2/event/filter
MARINE_TRAFFIC_API_KEY=      # marinetraffic.com

REVERB_APP_ID=ovnex
REVERB_APP_KEY=ovnex_key
REVERB_APP_SECRET=gizli_secret
REVERB_HOST=0.0.0.0
REVERB_PORT=8080
```

---

## HAFTA 1 — Migrations ve Modeller

### Öncelikli Migration Sırası
1. `aircraft_positions`
2. `earthquakes`
3. `weather_snapshots`
4. `news_feed`
5. `traffic_incidents`
6. `vessel_positions`
7. `camera_sources`
8. `ad_impressions`
9. `system_logs`

---

## HAFTA 2 — Collector Servisleri

### Her Servis için:
1. `app/Services/` altında servis sınıfı
2. `app/Jobs/` altında Job sınıfı
3. `app/Console/Kernel.php`'de scheduler kaydı
4. Test komutu: `php artisan tinker` ile manuel tetikleme

### Çalışma Frekansları
| Servis | Frekans | Sebep |
|--------|---------|-------|
| AircraftCollector | 10 saniye | Uçaklar hızlı hareket eder |
| EarthquakeCollector | 1 dakika | AFAD API limiti |
| WeatherCollector | 15 dakika | Hava saatte değişmez |
| NewsCollector | 5 dakika | Haber akışı |
| TrafficCollector | 2 dakika | Trafik anlık değişir |
| VesselCollector | 30 saniye | Gemiler yavaş ama takip edilmeli |

---

## HAFTA 3-4 — Frontend

### Harita Kurulumu
```html
<!-- resources/views/layouts/app.blade.php -->
<!-- Leaflet CSS/JS CDN -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- D3.js (Entity grafiği için) -->
<script src="https://d3js.org/d3.v7.min.js"></script>

<!-- ApexCharts (grafikler için) -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
```

### Harita Başlangıç Koordinatları
```javascript
// Şanlıurfa merkezi
const map = L.map('map').setView([37.1591, 38.7969], 12);

// Türkiye genel görünüm
// setView([39.0, 35.0], 6)
```

---

## HAFTA 5-6 — WebSocket Yayını

```php
// app/Events/AircraftUpdated.php
class AircraftUpdated implements ShouldBroadcast {
    public function broadcastOn() {
        return new Channel('aircraft.live');
    }
}
```

```javascript
// Frontend Echo listener
Echo.channel('aircraft.live')
    .listen('AircraftUpdated', (data) => {
        updateAircraftOnMap(data.aircraft);
    });
```

---

## HAFTA 7-8 — Reklam Entegrasyonu

### Google AdSense Adımları
1. adsense.google.com'a başvur
2. Site onaylanınca `ca-pub-XXXXXXXX` ID al
3. Auto ads etkinleştir
4. Manuel birim kodlarını stratejik yerlere ekle

### Reklam Konumları (Öncelikli)
```
1. Header altı — Leaderboard (728x90)
2. Sağ panel üstü — Rectangle (300x250)
3. Haber listesi arası — Native (300x250)
4. Mobil footer — Banner (320x50)
5. Video — Pre-roll (Faz 2)
```

---

## DEPLOYMENT CHECK LİSTESİ

### Production'a Almadan Önce
- [ ] `APP_DEBUG=false`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] Nginx konfigürasyonu
- [ ] SSL sertifikası (Certbot)
- [ ] Supervisor konfigürasyonu (Horizon + Reverb)
- [ ] CloudFlare aktif
- [ ] Cron job: `* * * * * php /var/www/ovnex/artisan schedule:run`
- [ ] Log rotation: `/etc/logrotate.d/ovnex`
- [ ] DB backup scripti
