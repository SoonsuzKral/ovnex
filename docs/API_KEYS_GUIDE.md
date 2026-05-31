# OVNEX — API Anahtarları Kılavuzu

> Bu dokümanda OVNEX'in kullandığı tüm dış API servislerine nasıl kaydolacağınız ve anahtarları nereden alacağınız adım adım açıklanmıştır.

---

## 1. OpenSky Network (Uçak Takibi)

**İhtiyaç:** OAuth2 Client Credentials (Client ID + Client Secret)

**Adımlar:**
1. https://opensky-network.org/index.php?option=com_users&view=registration adresine git
2. E-posta ve kullanıcı adı ile ücretsiz kaydol
3. E-postanı doğrula
4. Giriş yap → My Account → API v2 bölümünden Client ID ve Client Secret al
5. `.env` dosyasına yaz:
   ```
   OPENSKY_CLIENT_ID=client-id-buraya-yaz
   OPENSKY_CLIENT_SECRET=client-secret-buraya-yaz
   ```

**Limit:** Ücretsiz hesapta dakikada 10 istek (10 saniyede 1 yeterli)

---

## 2. OpenWeatherMap (Hava Durumu)

**İhtiyaç:** API Key (string)

**Adımlar:**
1. https://home.openweathermap.org/users/sign_up adresine git
2. Ücretsiz kaydol
3. Giriş yap → API Keys sekmesi → `default` key'i kopyala
4. `.env` dosyasına yaz:
   ```
   OPENWEATHER_API_KEY=buraya-openweather-api-key-yaz
   ```

**Limit:** Ücretsiz planda dakikada 60 istek, günde 1.000.000 çağrı

---

## 3. TomTom Traffic (Trafik Yoğunluğu)

**İhtiyaç:** API Key (string)

**Adımlar:**
1. https://developer.tomtom.com/user/register adresine git
2. Ücretsiz kaydol
3. https://developer.tomtom.com/user/me/apps adresinden "Create a new App"
4. App'e "OVNEX" ismini ver
5. "API Key"i kopyala
6. `.env` dosyasına yaz:
   ```
   TOMTOM_API_KEY=buraya-tomtom-api-key-yaz
   ```

**Limit:** Ücretsiz planda ayda 2.500 işlem (günde ~80 istek)

---

## 4. MarineTraffic (Gemi Takibi)

**İhtiyaç:** API Key (string)

**Adımlar:**
1. https://www.marinetraffic.com/register adresine git
2. Ücretsiz kaydol
3. Giriş yap → My Account → API Tokens
4. "Generate Token" tıkla
5. Token'ı kopyala
6. `.env` dosyasına yaz:
   ```
   MARINE_TRAFFIC_API_KEY=abcdef1234567890abcdef1234567890
   ```

**Limit:** Ücretsiz planda dakikada 1 istek, günde 1.440 istek

---

## 5. AFAD Deprem API

**İhtiyaç:** Yok (Herkese Açık)

API zaten public olduğu için anahtar gerekmez. `.env`'deki URL hazır:
```
AFAD_API_URL=https://deprem.afad.gov.tr/apiv2/event/filter
```

---

## 6. Google AdSense (Reklam)

**İhtiyaç:** Publisher ID (ca-pub-XXXXXXXX)

**Adımlar:**
1. https://adsense.google.com adresine git
2. Google hesabınla giriş yap
3. Site URL'ni ekle (ovnex.io)
4. Site onaylanana kadar bekle (1-7 gün)
5. Onay sonrası `ca-pub-XXXXXXXX` ID'ni al
6. `.env` dosyasına yaz:
   ```
   ADSENSE_PUBLISHER_ID=ca-pub-1234567890123456
   ```

**Önemli:** AdSense, sitenin yeterli trafiği olana kadar onay vermeyebilir. Alternatif: Ezoic.

---

## Hızlı Kontrol Listesi

| Servis | .env Değişkeni | Kayıt Linki | Ücret |
|--------|---------------|-------------|-------|
| OpenSky | `OPENSKY_CLIENT_ID`, `OPENSKY_CLIENT_SECRET` | [Kaydol](https://opensky-network.org) | Ücretsiz |
| OpenWeatherMap | `OPENWEATHER_API_KEY` | [Kaydol](https://home.openweathermap.org/users/sign_up) | Ücretsiz |
| TomTom | `TOMTOM_API_KEY` | [Kaydol](https://developer.tomtom.com) | Ücretsiz (2.500 işlem/ay) |
| MarineTraffic | `MARINE_TRAFFIC_API_KEY` | Alternatif: [AISHub](https://www.aishub.net) (ücretsiz) | Ücretsiz |
| AFAD | Yok (public) | Yok | Ücretsiz |
| AdSense | `ADSENSE_PUBLISHER_ID` | [Kaydol](https://adsense.google.com) | Ücretsiz (komisyonlu) |

---

## Test Adımları

Tüm API anahtarlarını `.env`'ye ekledikten sonra:

```bash
# Tek servis testi
php artisan tinker
>>> app(App\Services\OpenSkyService::class)->fetchAircraft();

# Tüm servisler
php artisan ovnex:collect-all
php artisan queue:work --once

# Log kontrol
SELECT * FROM system_logs ORDER BY created_at DESC LIMIT 20;
```
