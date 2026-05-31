# OVNEX — ROADMAP.md

## Geliştirme Yol Haritası

---

## FAZ 1 — MVP "İlk Göz Açma" (Ay 1-3)

### Sprint 1 — Temel Altyapı (Hafta 1-2)
- [ ] Laravel 11 proje kurulumu
- [ ] PostgreSQL + PostGIS kurulumu
- [ ] Redis kurulumu
- [ ] `.env` yapılandırması (API key'ler)
- [ ] Temel klasör yapısı (`app/Services`, `app/Jobs`, `app/Models`)
- [ ] CloudFlare DNS + SSL

### Sprint 2 — Harita Altyapısı (Hafta 3-4)
- [ ] Leaflet.js entegrasyonu
- [ ] OpenStreetMap tile layer
- [ ] Şanlıurfa merkez koordinatı (37.1591° N, 38.7969° E)
- [ ] Zoom seviyesi ayarı (Şanlıurfa: zoom 12, Türkiye: zoom 6)
- [ ] Harita katman seçici (Layer Control)
- [ ] Responsive sidebar layout

### Sprint 3 — Uçak Takibi (Hafta 5-6)
- [ ] OpenSky Network API bağlantısı
- [ ] `AircraftCollectorJob` (her 10sn çalışır)
- [ ] `aircraft_positions` tablosu
- [ ] Uçak ikonları haritada
- [ ] Uçak popup (uçuş no, yükseklik, hız, ülke)
- [ ] WebSocket ile canlı güncelleme

### Sprint 4 — Deprem & Afet (Hafta 7-8)
- [ ] AFAD Deprem API bağlantısı
- [ ] Kandilli RSS toplayıcı
- [ ] `earthquakes` tablosu
- [ ] Haritada deprem daireleri (büyüklüğe göre renk)
- [ ] ≥4.0 için banner uyarı
- [ ] Son 24 saat deprem listesi (sağ panel)

### Sprint 5 — Hava Durumu (Hafta 9-10)
- [ ] OpenWeatherMap API bağlantısı
- [ ] Hava durumu harita overlay (yağmur/sıcaklık)
- [ ] Şanlıurfa anlık widget
- [ ] 5 günlük tahmin paneli
- [ ] `weather_snapshots` tablosu

### Sprint 6 — Haber Akışı & Reklam (Hafta 11-12)
- [ ] RSS Toplayıcı servisi (AA, TRT, yerel gazeteler)
- [ ] `news_feed` tablosu
- [ ] Canlı haber paneli (sağ sidebar)
- [ ] Kategorilere göre harita pinleri
- [ ] Google AdSense entegrasyonu
- [ ] Banner reklam birimleri yerleşimi

---

## FAZ 2 — Genişleme "Güç Kazanma" (Ay 4-6)

### Sprint 7 — Trafik Sistemi
- [ ] TomTom Traffic API entegrasyonu
- [ ] Trafik yoğunluğu renk katmanı
- [ ] Trafik olayları (kaza, yol kapanması)
- [ ] `traffic_incidents` tablosu
- [ ] Şanlıurfa ana yolları özel takip

### Sprint 8 — Gemi Takibi
- [ ] MarineTraffic API entegrasyonu
- [ ] Gemi ikonları haritada
- [ ] Türkiye kıyıları odak
- [ ] `vessel_positions` tablosu

### Sprint 9 — OSINT Paneli
- [ ] Entity ilişki grafiği (D3.js)
- [ ] Olay zaman çizelgesi
- [ ] Çoklu kaynak eşleştirme
- [ ] Gelişmiş filtreler

### Sprint 10 — Video Reklam & Monetizasyon
- [ ] Video geçiş reklam sistemi
- [ ] Interstitial reklam
- [ ] Reklam frekans kontrolü
- [ ] `ad_impressions` tablosu
- [ ] Reklam performans dashboard

### Sprint 11 — Mobil & PWA
- [ ] PWA manifest
- [ ] Service Worker
- [ ] Offline temel sayfa
- [ ] Push notification (deprem uyarıları)
- [ ] Mobil sticky banner

---

## FAZ 3 — Pro "Olgunlaşma" (Ay 7+)

- [ ] Belediye MOBESE kamera entegrasyonu (API görüşmesi)
- [ ] ML tabanlı trafik tahmini
- [ ] Sosyal medya OSINT akışı (X/Twitter trends)
- [ ] API satışı (B2B, aylık abonelik)
- [ ] Çoklu şehir desteği (Gaziantep, Diyarbakır)
- [ ] Admin dashboard (içerik yönetimi)
- [ ] SEO optimizasyonu

---

## KRİTİK BAĞIMLILIKLAR

```
OpenSky API ──────────────► AircraftCollectorJob
AFAD API ─────────────────► EarthquakeCollectorJob
OpenWeatherMap API ───────► WeatherCollectorJob
TomTom API ───────────────► TrafficCollectorJob
RSS Feeds ────────────────► NewsCollectorJob
                                    │
                              Redis Queue
                                    │
                            WebSocket (Reverb)
                                    │
                           Leaflet.js Frontend
```

---

## BAŞARI KRİTERLERİ

| Faz | Kullanıcı | Reklam Geliri/Ay |
|-----|-----------|-----------------|
| MVP Sonrası | 1.000/gün | ~$50-100 |
| Faz 2 Sonrası | 10.000/gün | ~$500-1.000 |
| Faz 3 Sonrası | 50.000/gün | ~$3.000-8.000 |

---

## YAPILANLAR / EKSİKLER — Güncel Durum (v1.2.0)

### ✅ TAMAMLANANLAR (Kod)

| # | Madde | Durum |
|---|-------|-------|
| 1 | Laravel 12 proje kurulumu (Reverb + Predis) | ✅ |
| 2 | `.env` OVNEX yapılandırması | ✅ |
| 3 | 9 adet MySQL migration dosyası | ✅ |
| 4 | 9 adet Eloquent Model ($fillable + HasFactory) | ✅ |
| 5 | 7 adet Service (OpenSky, AFAD, OpenWeather, RSS, TomTom, MarineTraffic, Geocoding) | ✅ |
| 6 | 6 adet Queue Job (failed metotlu) | ✅ |
| 7 | 3 adet WebSocket Event (ShouldBroadcast) | ✅ |
| 8 | 7 adet REST Controller (JSON dönen) | ✅ |
| 9 | 13 API Route (api.php + web.php) | ✅ |
| 10 | Bootstrap/app.php'de api routing kaydı | ✅ |
| 11 | Palantir dark tema Blade layout (3 kolon) | ✅ |
| 12 | Leaflet.js harita + CartoDB Dark Matter | ✅ |
| 13 | Katman kontrolleri (uçak, deprem, trafik, hava, gemi) | ✅ |
| 14 | WebSocket.js (Laravel Echo + Reverb) | ✅ |
| 15 | 5 adet reklam birimi placeholder | ✅ |
| 16 | Scheduler kayıtları (console.php) | ✅ |
| 17 | `php artisan ovnex:collect-all` komutu | ✅ |
| 18 | README.md (OVNEX özel) | ✅ |
| 19 | .gitignore (Laravel default) | ✅ |
| 20 | .env.example (API key'ler placeholder) | ✅ |
| 21 | CHANGELOG.md | ✅ |
| 22 | VERSION dosyası | ✅ |
| 23 | Git init + GitHub remote (SoonsuzKral/ovnex) | ✅ |
| 24 | GitHub'a push (v1.0.0 + v1.1.0 tag ile) | ✅ |
| 25 | GitHub Actions CI/CD workflow | ✅ |
| 26 | Reverb + Broadcasting config yayınlandı | ✅ |
| 27 | SQLite ile migrate + seed + test (44/44) | ✅ |
| 28 | Laravel Breeze auth (login, register, profil) | ✅ |
| 29 | API rate limiting (60 istek/dk) | ✅ |
| 30 | Model Factory'ler (3 adet) | ✅ |
| 31 | Unit Test'ler (3 adet) | ✅ |
| 32 | Admin kullanıcı seeder (admin@ovnex.io) | ✅ |
| 33 | Admin stats sayfası auth korumalı | ✅ |
| 34 | Reverb meta tag'leri layout'a eklendi | ✅ |
| 35 | MySQL 8.4 kurulumu + tüm migration'lar çalıştı | ✅ |
| 36 | OpenSky OAuth2 client credentials desteği | ✅ |
| 37 | OpenWeather API key eklendi | ✅ |
| 38 | TomTom API key eklendi | ✅ |
| 39 | OpenSky token expiry handling (otomatik refresh) | ✅ |
| 40 | MarineTraffic API key yokken graceful skip | ✅ |
| 41 | AISHub alternatif gemi takip servisi | ✅ |
| 42 | `php artisan ovnex:collect-sync` komutu (queue'suz) | ✅ |
| 43 | GitHub Actions scheduled data collection workflow | ✅ |
| 44 | docs/FREE_HOSTING.md (ücretsiz hosting rehberi) | ✅ |
| 45 | v1.2.0 tag + GitHub push | ✅ |

### ❌ EKSİKLER / YAPILACAKLAR

| # | Madde | Öncelik | Not |
|---|-------|---------|-----|
| 1 | AdSense onayı | Yüksek | Site trafiği başlayınca |
| 2 | RBAC middleware implementasyonu | Orta | RBAC_MAPPING.csv hazır, kod yok |
| 3 | Laravel Horizon kurulumu | Orta | Windows'ta pcntl yok. Production'da kurulacak |
| 4 | Vite derleme (üretim) | Düşük | Şu an CDN kullanılıyor |
| 5 | Supervisor config | Orta | Production deployment aşamasında |
| 6 | Admin panel (Telescope/Sentry) | Düşük | Production monitoring |
| 7 | spatie/laravel-tags paketi | Düşük | ACTION_PLAN'de belirtilmişti |

### 🔄 DEVAM EDEN

| # | İşlem | Açıklama |
|---|-------|----------|
| 1 | Free hosting deploy | docs/FREE_HOSTING.md'ye göre Railway/Render + TiDB |
| 2 | Domain + SSL + CloudFlare | Hazır, hosting deploy sonrası bağlanacak |
| 3 | Faz 2 modülleri | OSINT paneli, PWA, video reklam |

---

*OVNEX © 2025 — Osman & Vildan Projesi*
