# OVNEX Changelog

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
