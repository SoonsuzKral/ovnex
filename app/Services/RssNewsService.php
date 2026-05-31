<?php
/*
 * OVNEX — RSS Haber servisi
 * Türkiye'deki ana haber kaynaklarından (AA, TRT, Hürriyet) RSS beslemelerini toplar
 * Kategori ve il tespiti yaparak haberleri sınıflandırır
 */
namespace App\Services;

use App\Models\NewsFeed;
use App\Models\SystemLog;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class RssNewsService
{
    protected Client $httpClient;

    protected array $kaynaklar = [
        'aa'    => 'https://www.aa.com.tr/tr/rss/default?cat=guncel',
        'trt'   => 'https://www.trthaber.com/sondakika.rss',
        'hurriyet' => 'https://www.hurriyet.com.tr/rss/anasayfa',
    ];

    protected array $sehirler = [
        'Adana', 'Adıyaman', 'Afyonkarahisar', 'Ağrı', 'Aksaray', 'Amasya',
        'Ankara', 'Antalya', 'Ardahan', 'Artvin', 'Aydın', 'Balıkesir',
        'Bartın', 'Batman', 'Bayburt', 'Bilecik', 'Bingöl', 'Bitlis',
        'Bolu', 'Burdur', 'Bursa', 'Çanakkale', 'Çankırı', 'Çorum',
        'Denizli', 'Diyarbakır', 'Düzce', 'Edirne', 'Elazığ', 'Erzincan',
        'Erzurum', 'Eskişehir', 'Gaziantep', 'Giresun', 'Gümüşhane',
        'Hakkari', 'Hatay', 'Iğdır', 'Isparta', 'İstanbul', 'İzmir',
        'Kahramanmaraş', 'Karabük', 'Karaman', 'Kars', 'Kastamonu',
        'Kayseri', 'Kırıkkale', 'Kırklareli', 'Kırşehir', 'Kilis',
        'Kocaeli', 'Konya', 'Kütahya', 'Malatya', 'Manisa', 'Mardin',
        'Mersin', 'Muğla', 'Muş', 'Nevşehir', 'Niğde', 'Ordu',
        'Osmaniye', 'Rize', 'Sakarya', 'Samsun', 'Siirt', 'Sinop',
        'Sivas', 'Şanlıurfa', 'Şırnak', 'Tekirdağ', 'Tokat', 'Trabzon',
        'Tunceli', 'Uşak', 'Van', 'Yalova', 'Yozgat', 'Zonguldak',
    ];

    protected array $kategoriAnahtar = [
        'earthquake' => ['deprem', 'sarsıntı', 'şiddetinde'],
        'fire'       => ['yangın', 'yanıyor', 'alev'],
        'traffic'    => ['kaza', 'çarpıştı', 'trafik kazası'],
        'flood'      => ['sel', 'taşkın', 'su baskını'],
        'conflict'   => ['çatışma', 'operasyon', 'saldırı', 'bomba'],
        'emergency'  => ['ohal', 'afet', 'acil durum'],
        'weather'    => ['fırtına', 'don', 'dolu', 'hortum'],
    ];

    public function __construct()
    {
        $this->httpClient = new Client([
            'timeout' => 10.0,
        ]);
    }

    public function fetchAll(): array
    {
        $allNews = [];

        foreach ($this->kaynaklar as $kaynakAdi => $url) {
            try {
                $haberler = $this->fetchSource($kaynakAdi, $url);
                $allNews = array_merge($allNews, $haberler);
            } catch (\Exception $e) {
                Log::warning("RSS {$kaynakAdi} basarisiz: " . $e->getMessage());
            }
        }

        return $allNews;
    }

    public function fetchSource(string $kaynakAdi, string $url): array
    {
        $basla = microtime(true);
        $records = [];
        $inserted = 0;

        try {
            $response = $this->httpClient->get($url);
            $xml = simplexml_load_string($response->getBody());

            if (!$xml) throw new \Exception("XML parse hatasi: {$url}");

            $items = $xml->channel->item ?? [];
            $fetched = count($items);

            foreach ($items as $item) {
                $title = trim((string) $item->title);
                $link = trim((string) $item->link);
                $desc = trim((string) $item->description);
                $pubDate = trim((string) $item->pubDate);

                if (!$title || !$link) continue;

                $varsa = NewsFeed::where('external_url', $link)->exists();
                if ($varsa) continue;

                $kategori = $this->detectCategory($title);
                $province = $this->detectProvince($title);

                $records[] = [
                    'external_url' => $link,
                    'source_name'  => $kaynakAdi,
                    'source_type'  => 'rss',
                    'title'        => $title,
                    'summary'      => $desc ?: null,
                    'category'     => $kategori,
                    'severity'     => 'low',
                    'province'     => $province,
                    'published_at' => $pubDate ? date('Y-m-d H:i:s', strtotime($pubDate)) : now(),
                    'is_verified'  => false,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];

                $inserted++;
            }

            if (!empty($records)) {
                foreach ($records as $rec) {
                    try {
                        NewsFeed::create($rec);
                    } catch (\Exception $e) {
                        // duplicate skip
                    }
                }
            }

            $this->logSuccess("rss_{$kaynakAdi}", 'fetch', $fetched, $inserted, $basla);

        } catch (\Exception $e) {
            $this->logError("rss_{$kaynakAdi}", 'fetch', $e->getMessage(), $basla);
        }

        return $records;
    }

    protected function detectCategory(string $title): string
    {
        $lower = mb_strtolower($title, 'UTF-8');
        foreach ($this->kategoriAnahtar as $kat => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($lower, $kw)) return $kat;
            }
        }
        return 'general';
    }

    protected function detectProvince(string $title): ?string
    {
        foreach ($this->sehirler as $sehir) {
            if (str_contains($title, $sehir)) return $sehir;
        }
        return null;
    }

    protected function logSuccess(string $service, string $action, int $fetched, int $inserted, float $basla): void
    {
        SystemLog::create([
            'service'          => $service,
            'action'           => $action,
            'status'           => 'success',
            'records_fetched'  => $fetched,
            'records_inserted' => $inserted,
            'duration_ms'      => (int) ((microtime(true) - $basla) * 1000),
        ]);
    }

    protected function logError(string $service, string $action, string $error, float $basla): void
    {
        SystemLog::create([
            'service'        => $service,
            'action'         => $action,
            'status'         => 'failed',
            'duration_ms'    => (int) ((microtime(true) - $basla) * 1000),
            'error_message'  => $error,
        ]);
    }
}
