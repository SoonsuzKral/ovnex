<?php
/*
 * OVNEX — Demo veri tohumlayıcı
 * Test ve geliştirme ortamı için örnek kayıtlar oluşturur
 */
namespace Database\Seeders;

use App\Models\AircraftPosition;
use App\Models\Earthquake;
use App\Models\WeatherSnapshot;
use App\Models\NewsFeed;
use App\Models\TrafficIncident;
use App\Models\VesselPosition;
use App\Models\CameraSource;
use App\Models\AdImpression;
use App\Models\SystemLog;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $simdi = now();

        // Demo uçaklar
        $aircraftData = [
            ['icao24' => '4b1801', 'callsign' => 'THY123', 'origin_country' => 'Türkiye', 'latitude' => 37.1591, 'longitude' => 38.7969, 'altitude_baro' => 10668, 'velocity' => 250, 'heading' => 180, 'vertical_rate' => 0, 'on_ground' => false, 'recorded_at' => $simdi],
            ['icao24' => '4b1802', 'callsign' => 'PGS456', 'origin_country' => 'Türkiye', 'latitude' => 37.2500, 'longitude' => 39.0000, 'altitude_baro' => 9144, 'velocity' => 220, 'heading' => 270, 'vertical_rate' => -2, 'on_ground' => false, 'recorded_at' => $simdi],
            ['icao24' => '4b1803', 'callsign' => 'SHS789', 'origin_country' => 'Almanya', 'latitude' => 37.0500, 'longitude' => 38.5000, 'altitude_baro' => 12192, 'velocity' => 280, 'heading' => 90, 'vertical_rate' => 3, 'on_ground' => false, 'recorded_at' => $simdi],
        ];
        foreach ($aircraftData as $data) AircraftPosition::create($data);

        // Demo depremler
        $earthquakeData = [
            ['external_id' => 'afad-2025-001', 'source' => 'AFAD', 'latitude' => 37.3500, 'longitude' => 38.8000, 'depth_km' => 12.5, 'magnitude' => 4.2, 'magnitude_type' => 'ML', 'location_name' => 'Şanlıurfa Merkez', 'province' => 'Şanlıurfa', 'occurred_at' => $simdi->copy()->subHours(2)],
            ['external_id' => 'afad-2025-002', 'source' => 'AFAD', 'latitude' => 38.0000, 'longitude' => 37.5000, 'depth_km' => 8.3, 'magnitude' => 3.8, 'magnitude_type' => 'ML', 'location_name' => 'Kahramanmaraş', 'province' => 'Kahramanmaraş', 'occurred_at' => $simdi->copy()->subHours(5)],
            ['external_id' => 'afad-2025-003', 'source' => 'KANDILLI', 'latitude' => 40.0000, 'longitude' => 26.5000, 'depth_km' => 15.0, 'magnitude' => 2.9, 'magnitude_type' => 'ML', 'location_name' => 'Çanakkale Açıkları', 'province' => 'Çanakkale', 'occurred_at' => $simdi->copy()->subHours(8)],
        ];
        foreach ($earthquakeData as $data) Earthquake::create($data);

        // Demo hava durumu
        $weatherData = [
            ['city' => 'Şanlıurfa', 'latitude' => 37.1591, 'longitude' => 38.7969, 'temperature_c' => 32.5, 'feels_like_c' => 34.0, 'humidity_pct' => 25, 'wind_speed_ms' => 3.5, 'wind_direction' => 180, 'condition_code' => 'Clear', 'condition_text' => 'Açık', 'condition_icon' => '01d', 'visibility_km' => 10.0, 'pressure_hpa' => 1012, 'uv_index' => 7, 'rainfall_mm' => 0, 'snow_mm' => 0, 'recorded_at' => $simdi],
            ['city' => 'Gaziantep', 'latitude' => 37.0662, 'longitude' => 37.3833, 'temperature_c' => 30.0, 'feels_like_c' => 31.0, 'humidity_pct' => 30, 'wind_speed_ms' => 4.0, 'wind_direction' => 200, 'condition_code' => 'Clear', 'condition_text' => 'Açık', 'condition_icon' => '01d', 'visibility_km' => 10.0, 'pressure_hpa' => 1013, 'uv_index' => 6, 'rainfall_mm' => 0, 'snow_mm' => 0, 'recorded_at' => $simdi],
            ['city' => 'Ankara', 'latitude' => 39.9334, 'longitude' => 32.8597, 'temperature_c' => 25.0, 'feels_like_c' => 24.0, 'humidity_pct' => 45, 'wind_speed_ms' => 5.2, 'wind_direction' => 270, 'condition_code' => 'Clouds', 'condition_text' => 'Parçalı Bulutlu', 'condition_icon' => '02d', 'visibility_km' => 8.0, 'pressure_hpa' => 1015, 'uv_index' => 4, 'rainfall_mm' => 0, 'snow_mm' => 0, 'recorded_at' => $simdi],
            ['city' => 'İstanbul', 'latitude' => 41.0082, 'longitude' => 28.9784, 'temperature_c' => 22.0, 'feels_like_c' => 21.0, 'humidity_pct' => 60, 'wind_speed_ms' => 6.0, 'wind_direction' => 340, 'condition_code' => 'Rain', 'condition_text' => 'Hafif Yağmurlu', 'condition_icon' => '10d', 'visibility_km' => 6.0, 'pressure_hpa' => 1010, 'uv_index' => 2, 'rainfall_mm' => 1.2, 'snow_mm' => 0, 'recorded_at' => $simdi],
        ];
        foreach ($weatherData as $data) WeatherSnapshot::create($data);

        // Demo haberler
        $newsData = [
            ['external_url' => 'https://aa.com.tr/ornek1', 'source_name' => 'AA', 'source_type' => 'rss', 'title' => 'Şanlıurfa\'da 4.2 büyüklüğünde deprem', 'summary' => 'AFAD verilerine göre Şanlıurfa merkezli 4.2 büyüklüğünde deprem meydana geldi.', 'category' => 'earthquake', 'severity' => 'high', 'province' => 'Şanlıurfa', 'published_at' => $simdi->copy()->subHours(2)],
            ['external_url' => 'https://trthaber.com/ornek2', 'source_name' => 'TRT', 'source_type' => 'rss', 'title' => 'İstanbul trafiğinde yoğunluk: Köprüde kaza', 'summary' => 'TEM Otoyolu\'nda meydana gelen trafik kazası nedeniyle uzun araç kuyrukları oluştu.', 'category' => 'traffic', 'severity' => 'medium', 'province' => 'İstanbul', 'published_at' => $simdi->copy()->subHours(3)],
            ['external_url' => 'https://hurriyet.com.tr/ornek3', 'source_name' => 'Hürriyet', 'source_type' => 'rss', 'title' => 'Gaziantep\'te yangın paniği', 'summary' => 'Organize sanayi bölgesinde çıkan yangın itfaiye ekiplerince kontrol altına alındı.', 'category' => 'fire', 'severity' => 'high', 'province' => 'Gaziantep', 'published_at' => $simdi->copy()->subHours(5)],
            ['external_url' => 'https://aa.com.tr/ornek4', 'source_name' => 'AA', 'source_type' => 'rss', 'title' => 'Diyarbakır\'da sel felaketi: 3 kişi kayıp', 'summary' => 'Sağanak yağış sonrası meydana gelen selde 3 kişiden haber alınamıyor.', 'category' => 'flood', 'severity' => 'critical', 'province' => 'Diyarbakır', 'published_at' => $simdi->copy()->subHours(6)],
            ['external_url' => 'https://trthaber.com/ornek5', 'source_name' => 'TRT', 'source_type' => 'rss', 'title' => 'Mardin\'de çatışma: Güvenlik güçlerinden operasyon', 'summary' => 'Kırsal alanda devam eden operasyonda çatışma çıktı.', 'category' => 'conflict', 'severity' => 'high', 'province' => 'Mardin', 'published_at' => $simdi->copy()->subHours(7)],
            ['external_url' => 'https://hurriyet.com.tr/ornek6', 'source_name' => 'Hürriyet', 'source_type' => 'rss', 'title' => 'OVNEX platformu kullanıma açıldı', 'summary' => 'Osman & Vildan Intelligence Nexus projesi ilk sürümüyle yayında.', 'category' => 'general', 'severity' => 'low', 'province' => 'Şanlıurfa', 'published_at' => $simdi->copy()->subHours(24)],
        ];
        foreach ($newsData as $data) NewsFeed::create($data);

        // Demo trafik olayları
        $trafficData = [
            ['external_id' => 'tomtom-001', 'incident_type' => 'accident', 'severity' => 3, 'description' => 'Maddi hasarlı trafik kazası', 'road_name' => 'D400 Karayolu', 'start_lat' => 37.1500, 'start_lng' => 38.8000, 'delay_seconds' => 1200, 'province' => 'Şanlıurfa', 'started_at' => $simdi->copy()->subHour()],
            ['external_id' => 'tomtom-002', 'incident_type' => 'jam', 'severity' => 2, 'description' => 'Yoğun trafik', 'road_name' => 'Atatürk Bulvarı', 'start_lat' => 37.1600, 'start_lng' => 38.7900, 'delay_seconds' => 600, 'province' => 'Şanlıurfa', 'started_at' => $simdi->copy()->subHours(2)],
        ];
        foreach ($trafficData as $data) TrafficIncident::create($data);

        // Demo gemiler
        $vesselData = [
            ['mmsi' => '271000001', 'vessel_name' => 'M/V OVNEX', 'vessel_type' => 'Cargo', 'flag' => 'TR', 'latitude' => 36.5000, 'longitude' => 29.0000, 'speed_knots' => 12.5, 'heading' => 45, 'destination' => 'Mersin', 'status' => 'Underway', 'recorded_at' => $simdi],
            ['mmsi' => '271000002', 'vessel_name' => 'URFA DENİZ', 'vessel_type' => 'Fishing', 'flag' => 'TR', 'latitude' => 36.8000, 'longitude' => 28.5000, 'speed_knots' => 5.0, 'heading' => 90, 'destination' => 'Fethiye', 'status' => 'Fishing', 'recorded_at' => $simdi],
        ];
        foreach ($vesselData as $data) VesselPosition::create($data);

        // Demo kameralar
        $cameraData = [
            ['name' => 'Balıklıgöl Kamerası', 'location_description' => 'Balıklıgöl Yerleşkesi Girişi', 'latitude' => 37.1489, 'longitude' => 38.7933, 'is_active' => true, 'province' => 'Şanlıurfa', 'district' => 'Eyyübiye'],
            ['name' => 'Akabe Caddesi', 'location_description' => 'Akabe Kavşağı', 'latitude' => 37.1550, 'longitude' => 38.8100, 'is_active' => true, 'province' => 'Şanlıurfa', 'district' => 'Karaköprü'],
        ];
        foreach ($cameraData as $data) CameraSource::create($data);

        // Demo reklam gösterimleri
        AdImpression::create(['ad_unit' => 'header_banner', 'ad_type' => 'banner', 'impressions' => 1250, 'clicks' => 12, 'country' => 'TR', 'recorded_at' => today()]);

        // Demo sistem logları
        SystemLog::create(['service' => 'opensky', 'action' => 'fetch', 'status' => 'success', 'records_fetched' => 42, 'records_inserted' => 42, 'duration_ms' => 1234]);
        SystemLog::create(['service' => 'afad', 'action' => 'fetch', 'status' => 'success', 'records_fetched' => 5, 'records_inserted' => 3, 'duration_ms' => 567]);

        $this->command->info('✅ Demo verileri başarıyla oluşturuldu!');
    }
}
