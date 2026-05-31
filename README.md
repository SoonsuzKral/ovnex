# OVNEX — Osman & Vildan Intelligence Nexus

> Türkiye'nin Gerçek Zamanlı OSINT Harita Portalı

## Özellikler

- **✈️ Canlı uçak takibi** — OpenSky Network API ile Türkiye semalarındaki uçaklar
- **🌍 Trafik yoğunluğu** — TomTom Traffic API ile anlık trafik olayları
- **🔴 Deprem & afet izleme** — AFAD Open API ile canlı deprem verileri
- **🌤️ Hava durumu katmanı** — OpenWeatherMap ile 5 şehir anlık hava durumu
- **📰 Canlı haber & olay akışı** — AA, TRT, Hürriyet RSS toplayıcı
- **🚢 Gemi takibi** — MarineTraffic API ile Türkiye kıyıları gemi konumları
- **📡 Gerçek zamanlı WebSocket** — Laravel Reverb ile anlık güncellemeler

## Kurulum

```bash
git clone https://github.com/ovnex/ovnex.git
cd ovnex
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

## Gereksinimler

- PHP 8.2+
- MySQL 8.0
- Composer 2.x
- Node.js & NPM (opsiyonel, Vite için)

## API Anahtarları

| Servis | Değişken | Kayıt |
|--------|----------|-------|
| OpenSky Network | OPENSKY_USERNAME, OPENSKY_PASSWORD | opensky-network.org |
| OpenWeatherMap | OPENWEATHER_API_KEY | openweathermap.org |
| TomTom Traffic | TOMTOM_API_KEY | developer.tomtom.com |
| MarineTraffic | MARINE_TRAFFIC_API_KEY | marinetraffic.com |

## Teknolojiler

| Bileşen | Teknoloji |
|---------|-----------|
| Backend | Laravel 11 / PHP 8.2 |
| Veritabanı | MySQL 8.0 |
| Gerçek Zamanlı | Laravel Reverb (WebSocket) |
| Harita | Leaflet.js + CartoDB Dark Matter |
| Frontend | Blade + Alpine.js + Tailwind CSS |
| Queue | Database driver |

## Lisans

MIT — Osman & Vildan Projesi © 2025
