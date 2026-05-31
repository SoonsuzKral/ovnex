# OVNEX — Osman & Vildan Intelligence Nexus
## Sistem Tanım Belgesi (SYSTEM.md)

---

## 1. PROJE KİMLİĞİ

| Alan | Değer |
|------|-------|
| **Proje Adı** | OVNEX |
| **Açılım** | Osman & Vildan Intelligence Nexus |
| **Versiyon** | 1.0.0 |
| **Tip** | OSINT Tabanlı Gerçek Zamanlı İstihbarat Portalı |
| **Kapsam** | Türkiye Geneli + Şanlıurfa Özel |
| **Framework** | Laravel 11 (PHP 8.3) |
| **Gelir Modeli** | Reklam (Display, Video, Geçiş, Banner) |
| **Auth** | Yok (Herkese Açık) |

---

## 2. VİZYON

OVNEX, Türkiye'nin ve Şanlıurfa'nın üzerindeki tüm hava, kara ve dijital hareketliliği; anlık kaza, afet, olay, hava durumu ve trafik verilerini tek bir ekranda birleştiren, Palantir'e rakip, JARVIS + Matrix esintili açık kaynak istihbarat portalıdır.

**Hedef Kullanıcı:**  
- Meraklı vatandaşlar  
- Gazeteciler & araştırmacılar  
- Yerel yönetim & trafik izleme  
- OSINT araştırmacıları  

**Temel Felsefe:**  
> "Her veri, doğru görselleştirildiğinde bir hikâye anlatır."

---

## 3. MİMARİ GENEL BAKIŞ

```
┌─────────────────────────────────────────────────────────┐
│                     OVNEX PLATFORM                      │
├────────────────┬────────────────┬───────────────────────┤
│  Veri Toplama  │   İşleme       │   Sunum Katmanı       │
│  (Collectors)  │   (Core)       │   (Frontend)          │
├────────────────┼────────────────┼───────────────────────┤
│ • ADS-B Uçak   │ • Laravel Jobs │ • Leaflet.js Harita   │
│ • OSM Trafik   │ • Redis Cache  │ • WebSocket Canlı     │
│ • AFAD/AKUT    │ • Queue Worker │ • Dark UI Dashboard   │
│ • Deprem API   │ • Scheduler    │ • Mobil Responsive    │
│ • Haber RSS    │ • Event System │ • Reklam Birimleri    │
│ • Kamera Feed  │ • DB Kayıt     │                       │
└────────────────┴────────────────┴───────────────────────┘
```

---

## 4. ANA MODÜLLER

### 4.1 Uçak Takip Modülü (AirTracker)
- **Kaynak:** OpenSky Network API, ADS-B Exchange
- **Gösterim:** Harita üzerinde canlı uçak ikonları
- **Veri:** ICAO kodu, irtifa, hız, yön, kalkış/varış noktası
- **Güncelleme:** Her 10 saniye
- **Filtre:** Şanlıurfa hava sahası (LTCG + çevre)

### 4.2 Trafik Yoğunluğu Modülü (TrafficPulse)
- **Kaynak:** TomTom Traffic API veya HERE Maps API
- **Gösterim:** Yollar üzerinde renk kodlu yoğunluk katmanı (yeşil/sarı/turuncu/kırmızı)
- **Veri:** Ortalama hız, sıkışıklık oranı, olay bildirimleri
- **Özellik:** Kullanıcı zoom yaptıkça detay artar
- **Şanlıurfa Odak:** GAP yolu, Atatürk Bulvarı, D400 güzergahı

### 4.3 Haber & Olay Akışı (LiveFeed)
- **Kaynak:** RSS Toplayıcı (AA, TRT, Şanlıurfa yerel gazeteler, AFAD, OHAL bildirimleri)
- **Kategoriler:** Deprem, Trafik Kazası, Yangın, Sel, OHAL, Savaş/Çatışma, Genel Olay
- **Gösterim:** Harita üzerine pin + sağ panel canlı feed
- **Filtre:** Şanlıurfa > Türkiye > Dünya

### 4.4 Deprem Modülü (SeismicWatch)
- **Kaynak:** AFAD Deprem API, Kandilli Rasathanesi RSS
- **Gösterim:** Harita üzerinde büyüklüğe göre ölçekli daireler
- **Veri:** Büyüklük, derinlik, merkez üssü, zaman
- **Alarm:** Büyüklük ≥ 4.0 için bildirim banner

### 4.5 Hava Durumu Katmanı (WeatherLayer)
- **Kaynak:** OpenWeatherMap API (ücretsiz + ücretli katman)
- **Gösterim:** Harita üzerinde yağmur/rüzgar/sıcaklık overlay
- **Veri:** Şanlıurfa anlık + 5 günlük tahmin
- **Özellik:** Radar görüntüsü overlay

### 4.6 Şehir Kameraları (CamWatch) — Gelecek Faz
- **Kaynak:** Belediye MOBESE entegrasyonu (API anlaşması gerekli)
- **Gösterim:** Harita üzerinde kamera ikonları → tıklayınca canlı feed
- **Not:** Faz 2'de geliştirilecek

### 4.7 Deniz/Gemi Takibi (MarineEye) — Opsiyonel
- **Kaynak:** MarineTraffic API (ücretsiz katman)
- **Kapsam:** Türkiye deniz hudutları, Akdeniz, Karadeniz

### 4.8 OSINT Paneli (IntelBoard)
- **Özellik:** Çoklu kaynak eşleştirme, olay zaman çizelgesi
- **Gösterim:** Entity ilişki grafiği (D3.js)
- **Filtre:** Zaman aralığı, olay tipi, coğrafya

---

## 5. TEKNOLOJİ STACK

### Backend
| Bileşen | Teknoloji | Versiyon |
|---------|-----------|---------|
| Framework | Laravel | 11.x |
| PHP | PHP | 8.3+ |
| DB | PostgreSQL | 16+ |
| Cache/Queue | Redis | 7+ |
| WebSocket | Laravel Reverb | 1.x |
| Scheduler | Laravel Scheduler | built-in |
| Job Queue | Laravel Horizon | 5.x |

### Frontend
| Bileşen | Teknoloji |
|---------|-----------|
| UI Framework | Blade + Alpine.js + Livewire |
| Harita | Leaflet.js 1.9 |
| Grafikler | Chart.js / ApexCharts |
| Entity Graf | D3.js |
| WebSocket Client | Laravel Echo + Pusher.js |
| CSS | Tailwind CSS 3 |
| İkonlar | Lucide / Heroicons |

### DevOps
| Bileşen | Teknoloji |
|---------|-----------|
| Sunucu | Ubuntu 22.04 LTS |
| Web Server | Nginx |
| Process Manager | Supervisor |
| SSL | Let's Encrypt |
| Deployment | GitHub Actions CI/CD |
| Monitoring | Laravel Telescope + Sentry |

---

## 6. DIŞ API ENTEGRASYONLARİ

| Servis | Amaç | Plan | Maliyet |
|--------|------|------|---------|
| **OpenSky Network** | Uçak verisi | Ücretsiz | $0 |
| **ADS-B Exchange** | Uçak yedek | Ücretsiz | $0 |
| **TomTom Traffic** | Trafik akışı | Freemium | $0-50/ay |
| **OpenWeatherMap** | Hava durumu | Freemium | $0-40/ay |
| **AFAD Open API** | Deprem/Afet | Ücretsiz | $0 |
| **Kandilli RSS** | Deprem | Ücretsiz | $0 |
| **OpenStreetMap/Nominatim** | Harita tile | Ücretsiz | $0 |
| **Google AdSense** | Reklam | Revenue share | Gelir |
| **MarineTraffic** | Gemi takibi | Freemium | $0-30/ay |
| **RSS Toplayıcı (AA, TRT)** | Haber akışı | Ücretsiz | $0 |

---

## 7. REKLAM GELİR MODELİ

### Reklam Türleri
1. **Leaderboard Banner** (728x90) — Üst kısım
2. **Rectangle Banner** (300x250) — Sağ panel
3. **Video Geçiş Reklamı** — 15-30 saniye, her 10 dakikada bir
4. **Interstitial (Tam Ekran Geçiş)** — Sayfa yüklemede, günde max 2
5. **Native Feed Reklamı** — Haber akışı içine karışık
6. **Sticky Footer Banner** — Mobilde her zaman görünür

### Reklam Ağları (Öncelik Sırasına Göre)
1. Google AdSense
2. Ezoic (otomatik optimizasyon)
3. Doğrudan satış (yerel Şanlıurfa reklamverenler)
4. Affiliate (trafik/hava uygulamaları referans)

---

## 8. VERİTABANI GENEL YAPISI

### Ana Tablolar
- `aircraft_positions` — Uçak anlık konum logları
- `traffic_incidents` — Trafik olayları
- `earthquakes` — Deprem kayıtları
- `news_feed` — Haber/olay akışı
- `weather_snapshots` — Hava durumu anlık verileri
- `camera_sources` — Kamera kayıtları
- `system_logs` — Sistem ve API logları
- `ad_impressions` — Reklam görüntüleme sayaçları

---

## 9. GÜVENLİK & PERFORMANS

- Login/Register yok → güvenlik yükü minimal
- Rate limiting: IP başına dakikada max 60 istek
- API key'leri .env ile saklanır, asla frontend'e çıkmaz
- CDN: CloudFlare ücretsiz plan (DDoS koruması + cache)
- Harita tile caching: Redis'te 1 saat TTL
- Uçak verisi: 10sn polling, WebSocket ile yayın
- Database indexing: coğrafi sorgular için PostGIS extension

---

## 10. FAZLAR

### Faz 1 — MVP (2-3 Ay)
- [ ] Laravel proje kurulumu
- [ ] Harita altyapısı (Leaflet)
- [ ] Uçak takibi (OpenSky API)
- [ ] Hava durumu katmanı
- [ ] Deprem modülü (AFAD)
- [ ] Temel haber RSS
- [ ] Google AdSense entegrasyonu

### Faz 2 — Genişleme (2-3 Ay)
- [ ] Trafik yoğunluğu (TomTom)
- [ ] Gemi takibi
- [ ] Video reklam sistemi
- [ ] Gelişmiş OSINT paneli
- [ ] Mobil PWA

### Faz 3 — Pro (Süregelen)
- [ ] Belediye kamera entegrasyonu
- [ ] Yapay zeka olay tespiti
- [ ] API satışı (B2B)
- [ ] Premium üyelik katmanı (opsiyonel)

---

*OVNEX © 2025 — Osman & Vildan Projesi*
