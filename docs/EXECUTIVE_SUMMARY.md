# OVNEX — Executive Summary

## Proje Özeti

**OVNEX (Osman & Vildan Intelligence Nexus)**, Türkiye'de ve özellikle Şanlıurfa'da gerçek zamanlı OSINT (Açık Kaynak İstihbaratı) tabanlı bir izleme ve görselleştirme platformudur.

---

## Problem

Türkiye'de anlık uçak takibi, trafik yoğunluğu, deprem, afet ve olay verilerini **tek bir harita üzerinde** gösteren, halka açık, Türkçe bir platform bulunmamaktadır.

---

## Çözüm

OVNEX, açık API'lar ve OSINT kaynaklarından veri toplayarak bunları Palantir tarzı bir arayüzde sunar. Kullanıcı kaydı gerekmez. Reklam gelirleriyle sürdürülebilirdir.

---

## Temel Özellikler

| # | Özellik | Durum |
|---|---------|-------|
| 1 | Gerçek zamanlı uçak takibi | ✅ v1.1.0 |
| 2 | Trafik yoğunluğu haritası | ✅ v1.1.0 |
| 3 | Deprem & afet bildirimleri | ✅ v1.1.0 |
| 4 | Canlı haber/olay akışı | ✅ v1.1.0 |
| 5 | Hava durumu overlay | ✅ v1.1.0 |
| 6 | Gemi & deniz takibi | ✅ v1.1.0 |
| 7 | Kullanıcı girişi (Auth) | ✅ v1.1.0 |
| 8 | Admin panel | ✅ v1.1.0 |
| 9 | WebSocket canlı veri | ✅ v1.1.0 |
| 10 | Şehir kameraları | ⏳ Faz 2 |
| 11 | OSINT entity grafiği | ⏳ Faz 2 |

---

## Teknoloji

- **Backend:** Laravel 12 / PHP 8.2 / MySQL (SQLite test)
- **Frontend:** Leaflet.js / Alpine.js / Tailwind CSS
- **Gerçek Zamanlı:** Laravel Reverb (Pusher protokolü)
- **Dağıtım:** Ubuntu + Nginx + CloudFlare

---

## Gelir Modeli

- Google AdSense banner reklamları
- Video geçiş reklamları
- Interstitial (tam ekran) reklamlar
- Mobil sticky banner
- İleride: doğrudan reklam satışı ve API erişim aboneliği

---

## Tahmini Maliyet (Aylık, MVP Sonrası)

| Kalem | Maliyet |
|-------|---------|
| Sunucu (VPS) | ~$20-40 |
| API'lar (TomTom, OWM) | ~$50-90 |
| CloudFlare Pro | $20 |
| **Toplam** | **~$90-150/ay** |

---

## Başarı Metrikleri

- 1. ayda 1.000 günlük aktif kullanıcı
- 3. ayda 10.000 günlük aktif kullanıcı
- AdSense onayı için 100+ sayfa/gün ≥ 30 gün
- Sayfa başı 3-5 reklam birimi

---

## Rekabet Avantajı

| Platform | Kapsam | Türkçe | OSINT | Şanlıurfa Odak |
|----------|--------|--------|-------|----------------|
| Flightradar24 | Uçak sadece | ❌ | ❌ | ❌ |
| Yandex Maps | Trafik | Kısmi | ❌ | ❌ |
| AFAD Sitesi | Deprem | ✅ | ❌ | ❌ |
| **OVNEX** | **Her şey** | **✅** | **✅** | **✅** |

---

*OVNEX — Şanlıurfa'nın Gözü, Türkiye'nin Nabzı*
