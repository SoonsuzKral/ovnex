# OVNEX Changelog

## [1.2.0] — 2026-06-01 — API Anahtarları & MySQL & Ücretsiz Hosting

### Eklenenler
- OpenSky API OAuth2 client credentials desteği (token refresh ile)
- OpenWeather API key eklendi
- TomTom API key eklendi
- MySQL 8.4 kurulumu + tüm migration'lar çalışır durumda
- `php artisan ovnex:collect-sync` komutu (kuyruk gerektirmez)
- AISHub alternatif gemi takip servisi (MarineTraffic ücretsiz alternatifi)
- GitHub Actions scheduled veri toplama workflow (her 30 dk)
- docs/FREE_HOSTING.md (tamamen ücretsiz hosting rehberi)
- OpenSky token expiry handling (60dk'da bir otomatik refresh)

### Düzeltmeler
- OpenSky Service: Basic Auth → OAuth2 Client Credentials
- MarineTraffic: API key yokken graceful skip (hata fırlatmaz)
- Migration: timestamp default value hatası düzeltildi (MySQL 8.4 strict mode)
- .env.example: OPENSKY_USERNAME/PASSWORD → OPENSKY_CLIENT_ID/OPENSKY_CLIENT_SECRET

## [1.1.0] — 2026-06-01 — İkinci Sürüm (Güncellendi)

### Eklenenler
- API anahtarları kılavuzu (docs/API_KEYS_GUIDE.md)
- Demo veri tohumlayıcı (Database\Seeders\DemoDataSeeder)
- Özel hata sayfaları (404, 500, 503)
- Admin sistem durumu sayfası (/admin/stats)
- Kapsamlı GitHub Actions CI/CD (PHP 8.2 + 8.3 matrisi)
- API endpoint testleri (12 test → 44 test)
- php artisan db:seed ile demo veriler
- Tüm artisan komutları test edildi
- **Laravel Breeze auth** (login, register, profil yönetimi)
- **API rate limiting** (60 istek/dakika)
- **Admin kullanıcı seeder** (admin@ovnex.io / admin123)
- **Model Factory'ler** (AircraftPosition, Earthquake, NewsFeed + HasFactory trait tüm modellerde)
- **3 Unit Test** (AircraftPosition, Earthquake, NewsFeed)
- **Admin sayfası auth koruması** (/admin/stats → yalnızca giriş yapanlar)
- **Reverb meta tag'leri** layout'a eklendi (WebSocket çalışır durumda)
- **Palantir tema Breeze ile uyumlu** (auth bağlantıları header'da)

### Düzeltmeler
- WebSocket.js'deki Vite import.meta.env referansı temizlendi
- Database configuration SQLite/MySQL uyumluluğu
- Admin-stats sayfasında log tablosu render edilmiyordu (düzeltildi)
- Breeze web.php'yi ezmişti (OVNEX rotaları geri yüklendi)
- Breeze layouts/app.blade.php'yi ezmişti (Palantir tema geri yüklendi)
- Dashboard route adı eklendi (Breeze navigasyonu için)

## [1.0.0] — 2026-05-31 — İlk Sürüm

### Eklenenler
- Laravel 12 proje kurulumu (Reverb + Predis)
- 9 adet MySQL migration (aircraft_positions, earthquakes, weather_snapshots, news_feed, traffic_incidents, vessel_positions, camera_sources, ad_impressions, system_logs)
- 9 adet Eloquent Model
- 7 adet Service (OpenSky, AFAD, OpenWeather, RSS, TomTom, MarineTraffic, Geocoding)
- 6 adet Queue Job (Aircraft, Earthquake, Weather, News, Traffic, Vessel)
- 3 adet WebSocket Event (AircraftUpdated, EarthquakeDetected, NewsReceived)
- 7 adet REST API Controller
- 13 API Route (RESTful)
- Palantir-style dark tema Blade layout + 12 component
- Leaflet.js harita entegrasyonu (CartoDB Dark Matter)
- Layer kontrolleri (uçak, deprem, trafik, hava, gemi)
- WebSocket canlı veri akışı (Laravel Reverb)
- Google AdSense reklam birimleri (placeholder)
- Scheduler tüm veri toplama frekansları
- php artisan ovnex:collect-all komutu
- README.md, CHANGELOG.md, .gitignore
- GitHub Actions CI/CD workflow
