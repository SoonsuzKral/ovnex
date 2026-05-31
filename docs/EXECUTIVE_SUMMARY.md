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
| 1 | Gerçek zamanlı uçak takibi | Faz 1 |
| 2 | Trafik yoğunluğu haritası | Faz 1 |
| 3 | Deprem & afet bildirimleri | Faz 1 |
| 4 | Canlı haber/olay akışı | Faz 1 |
| 5 | Hava durumu overlay | Faz 1 |
| 6 | Gemi & deniz takibi | Faz 2 |
| 7 | Şehir kameraları | Faz 2 |
| 8 | OSINT entity grafiği | Faz 2 |

---

## Teknoloji

- **Backend:** Laravel 11 / PHP 8.3 / PostgreSQL / Redis
- **Frontend:** Leaflet.js / Alpine.js / Livewire / Tailwind CSS
- **Gerçek Zamanlı:** Laravel Reverb (WebSocket)
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
