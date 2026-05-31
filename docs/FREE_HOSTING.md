# OVNEX — Ücretsiz Hosting Rehberi

> VDS/VPS parası olmayanlar için tamamen ücretsiz hosting çözümleri.

## Neden VDS Gerekmiyor?

OVNEX hafif bir uygulama:
- PHP 8.2+ çalıştıran her yer çalışır
- Çoğu free tier Laravel için yeterli (512MB RAM)
- Veri toplama GitHub Actions ile yapılır

## 1. Veritabanı (Seçenekler)

### Seçenek A: TiDB Serverless (ÖNERİLEN)
- **MySQL ile %100 uyumlu**
- 5GB'a kadar ücretsiz
- Kredi kartı GEREKMEZ

```bash
# 1. https://tidbcloud.com → Sign up (Google ile)
# 2. Create Cluster → Serverless Tier → bölge: AWS Frankfurt
# 3. Connection > "Connect with General connection" > Copy connection string
# 4. .env'ye yaz:
DB_CONNECTION=mysql
DB_HOST=gateway01.eu-central-1.prod.aws.tidbcloud.com
DB_PORT=4000
DB_DATABASE=ovnex
DB_USERNAME=xxxx.root
DB_PASSWORD=xxxxx
```

### Seçenek B: db4free.net
- MySQL 8.0, 200MB ücretsiz
- https://www.db4free.net → Kaydol
- 14 günde bir giriş yapmazsan silinir

### Seçenek C: Supabase (PostgreSQL)
- PostgreSQL, 500MB ücretsiz
- Laravel PostgreSQL driver ile çalışır
- DB_CONNECTION=pgsql olarak değiştir

## 2. Web Hosting (Seçenekler)

### Seçenek A: Render.com (ÖNERİLEN)
- Ücretsiz web servisi (15 dk hareketsizlikte uyur)
- Otomatik SSL, custom domain desteği
- Kredi kartı ister

**Render'a deploy:**
```bash
# 1. GitHub repo'nu Render'a bağla
# 2. New Web Service → repo seç
# 3. Build Command:
composer install --no-interaction --no-progress --prefer-dist
# 4. Start Command:
php artisan serve --host=0.0.0.0 --port=$PORT
# 5. Environment Variables:
APP_KEY, DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE
OPENSKY_CLIENT_ID, OPENSKY_CLIENT_SECRET, etc.
# 6. Deploy
```

### Seçenek B: Railway.app
- $5 kredi ile başlar (aylık 2-3$ harcarsan 2 ay gider)
- Built-in MySQL desteği
- Daha güçlü sunucular

### Seçenek C: Fly.io
- 3 adet paylaşımlı VM (256MB RAM) ücretsiz
- 3GB persistent volume
- CLI ile kurulum gerektirir

## 3. Scheduler (Zamanlanmış Görevler)

Render ücretsiz tier uyuyunca scheduler çalışmaz. Bunun yerine GitHub Actions kullan:

### GitHub Actions ile Veri Toplama
Her 30 dakikada bir çalışır, uygulamayı uyandırmaz.

Repository Settings > Secrets and variables > Actions:
```
OPENSKY_CLIENT_ID=...
OPENSKY_CLIENT_SECRET=...
OPENWEATHER_API_KEY=...
TOMTOM_API_KEY=...
```

Workflow zaten hazır: `.github/workflows/collect.yml`

### App'i Uyanık Tut (cron-job.org)
Render uyumasın diye 14dk'da bir ping at:

```bash
# 1. https://cron-job.org → Kaydol
# 2. Yeni cron job:
#    URL: https://ovnex.onrender.com/up
#    Every: 14 minutes
# 3. Kaydet
```

## 4. Adım Adım Deploy

```bash
# 1. TiDB Cloud'a kaydol → DB oluştur → bağlantı bilgilerini al

# 2. .env'yi güncelle:
DB_HOST=gateway01.eu-central-1.prod.aws.tidbcloud.com
DB_PORT=4000
DB_DATABASE=ovnex
DB_USERNAME=xxxx.root
DB_PASSWORD=xxxxx

# 3. Migration'ları çalıştır:
php artisan migrate --force
php artisan db:seed --force

# 4. GitHub'a pushla:
git add .
git commit -m "v1.2.0"
git tag v1.2.0
git push && git push --tags

# 5. Render.com'da deploy et

# 6. GitHub Actions'a secret'ları ekle:
#    Settings > Secrets and variables > Actions
#    OPENSKY_CLIENT_ID, OPENSKY_CLIENT_SECRET,
#    OPENWEATHER_API_KEY, TOMTOM_API_KEY

# 7. Domain ayarla:
#    CloudFlare'de CNAME: ovnex.io → ovnex.onrender.com
#    Render'da Settings > Custom Domain: ovnex.io

# 8. cron-job.org'da 14 dk'da bir ping ayarla
```

## 5. Ücretsiz Hosting Karşılaştırması

| Özellik | Render | Railway | Fly.io | TiDB |
|---------|--------|---------|--------|------|
| RAM | 512MB | 512MB | 256MB | - |
| Disk | - | - | 3GB | 5GB |
| Uyur mu? | Evet (15dk) | Hayır | Hayır | - |
| SSL | Otomatik | Otomatik | Otomatik | TLS |
| Custom Domain | Evet | Evet | Evet | - |
| Kredi Kartı | Evet | Evet | Evet | Hayır |

## 6. Maliyet: 0 TL

| Servis | Ne için | Ücret |
|--------|---------|-------|
| TiDB Serverless | Veritabanı | Ücretsiz |
| Render.com | Web hosting | Ücretsiz |
| GitHub Actions | Veri toplama | Ücretsiz |
| cron-job.org | Ping | Ücretsiz |
| CloudFlare | DNS + SSL | Ücretsiz |
| GitHub | Kod depolama | Ücretsiz |
| **Toplam** | | **0 TL/ay** |
