# OVNEX — Osman & Vildan Intelligence Nexus

> Türkiye'nin Gerçek Zamanlı OSINT Harita Portalı — Şanlıurfa Merkezli

## Özellikler

- ✈️ Canlı uçak takibi — OpenSky API ile Türkiye semaları
- 🌍 Trafik yoğunluğu — TomTom Traffic API ile anlık olaylar
- 🔴 Deprem & afet izleme — AFAD API ile canlı deprem verileri
- 🌤️ Hava durumu katmanı — OpenWeatherMap ile çok şehir
- 📰 Canlı haber & olay akışı — AA, TRT, Hürriyet RSS
- 🚢 Gemi takibi — MarineTraffic API ile Türkiye kıyıları
- 📡 Gerçek zamanlı WebSocket — Laravel Reverb
- 🔐 Kullanıcı girişi — Laravel Breeze (Blade) ile auth

## Hızlı Kurulum

```bash
git clone https://github.com/SoonsuzKral/ovnex.git
cd ovnex
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
# Giriş: admin@ovnex.io / admin123
```

## Gereksinimler

- PHP 8.2+
- MySQL 8.0 (veya SQLite test için)
- Composer 2.x
- Node.js & NPM (opsiyonel, Vite frontent için)

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
| Backend | Laravel 12 / PHP 8.2 |
| Veritabanı | MySQL 8.0 (SQLite test) |
| Auth | Laravel Breeze (Blade) |
| Gerçek Zamanlı | Laravel Reverb + Pusher |
| Harita | Leaflet.js + CartoDB Dark Matter |
| Frontend | Blade + Alpine.js + Tailwind CSS |
| Queue | Database driver |
| Test | PHPUnit (44 test, RefreshDatabase) |

## Test

```bash
php artisan test
# 44 tests, 93 assertions — tamamı yeşil
```

## Lisans

MIT — Osman & Vildan Projesi © 2025-2026
