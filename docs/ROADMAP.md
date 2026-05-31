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

## YAPILANLAR / EKSİKLER — Güncel Durum (v1.0.0)

> Bu bölüm her işlem sonrası güncellenir. Yeni pencere açıldığında buraya bak.

### ✅ TAMAMLANANLAR (Kod)

| # | Madde | Durum |
|---|-------|-------|
| 1 | Laravel 12 proje kurulumu (Reverb + Predis) | ✅ |
| 2 | `.env` OVNEX yapılandırması | ✅ |
| 3 | 9 adet MySQL migration dosyası | ✅ |
| 4 | 9 adet Eloquent Model ($fillable dolu) | ✅ |
| 5 | 7 adet Service (OpenSky, AFAD, OpenWeather, RSS, TomTom, MarineTraffic, Geocoding) | ✅ |
| 6 | 6 adet Queue Job (failed metotlu) | ✅ |
| 7 | 3 adet WebSocket Event (ShouldBroadcast) | ✅ |
| 8 | 7 adet REST Controller (JSON dönen) | ✅ |
| 9 | 13 API Route (api.php + web.php) | ✅ |
| 10 | Bootstrap/app.php'de api routing kaydı | ✅ |
| 11 | Palantir dark tema Blade layout (3 kolon) | ✅ |
| 12 | Leaflet.js harita + CartoDB Dark Matter | ✅ |
| 13 | Katman kontrolleri (uçak, deprem, trafik, hava, gemi) | ✅ |
| 14 | WebSocket.js (Laravel Echo + Pusher) | ✅ |
| 15 | 5 adet reklam birimi placeholder | ✅ |
| 16 | Scheduler kayıtları (console.php) | ✅ |
| 17 | `php artisan ovnex:collect-all` komutu | ✅ |
| 18 | README.md (OVNEX özel) | ✅ |
| 19 | .gitignore (Laravel default) | ✅ |
| 20 | .env.example (API key'ler placeholder) | ✅ |
| 21 | CHANGELOG.md (v1.0.0) | ✅ |
| 22 | VERSION dosyası (1.0.0) | ✅ |
| 23 | GitHub Actions CI/CD workflow | ✅ |
| 24 | Reverb + Broadcasting config publish | ✅ |
| 25 | Git init + GitHub remote (ovnex/ovnex) | ✅ |

### ❌ EKSİKLER / YAPILACAKLAR

| # | Madde | Öncelik | Not |
|---|-------|---------|-----|
| 1 | MySQL servisi kurulumu | Yüksek | Yerelde MySQL çalışmıyor, migrate çalıştırılamadı |
| 2 | API anahtarlarının temini | Yüksek | OPENSKY, OPENWEATHER, TOMTOM, MARINE_TRAFFIC boş |
| 3 | `php artisan migrate` çalıştırma | Yüksek | MySQL hazır olunca |
| 4 | Laravel Horizon kurulumu | Orta | Windows'ta pcntl yok. Production'da kurulacak |
| 5 | Redis servisi kurulumu | Orta | Yerelde Redis yok. Database driver kullanılıyor |
| 6 | Vite derleme | Düşük | Şu an CDN kullanılıyor. Production'da Vite'e geçilecek |
| 7 | Supervisor config | Orta | Production deployment aşamasında |
| 8 | SSL Sertifikası | Orta | Domain alınınca Let's Encrypt |
| 9 | CloudFlare DNS | Düşük | Domain alınınca |
| 10 | Google AdSense onayı | Yüksek | Site trafiği başlayınca |
| 11 | Admin panel (Telescope/Sentry) | Düşük | Production monitoring |

### 🔄 DEVAM EDEN

| # | İşlem | Açıklama |
|---|-------|----------|
| — | Bekleyen işlem yok | — |

---

*OVNEX © 2025 — Osman & Vildan Projesi*
