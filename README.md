# API-Docs Package

BR Technologies tərəfindən hazırlanmış API-Docs, Laravel tətbiqləri üçün API sənədləşdirməsini avtomatik olaraq yaratmaq və idarə etmək üçün bir paketdir. Bu paket, API sorğularınızı avtomatik olaraq qeyd edir və ətraflı sənədləşdirmə yaradır.

## Xüsusiyyətlər

- API sorğularının avtomatik qeydiyyatı
- Detallı API sənədləşdirməsi
- cURL komandalarının avtomatik generasiyası
- İP və API prefiksi üzərindən filtrasiya

## Quraşdırma

1. Composer vasitəsilə paketi yükləyin:

```bash
composer require br-tech/api-docs:dev-main
```

2. Servis provayderini config/app.php faylına əlavə edin (Laravel 5.5+ versiyalarda avtomatik olaraq əlavə olunur):
```php
'providers' => [
   Br\ApiDocsPackage\ApiDocsServiceProvider::class,
   ],
```
3. Konfiqurasiya faylını nəşr edin:
```bash
php artisan vendor:publish --provider="Br\ApiDocsPackage\ApiDocsServiceProvider" --tag="config"
```
4. Migrasiyaları işə salın:
```bash
php artisan migrate
```

## Konfiqurasiya
config/api-docs.php faylında aşağıdakı parametrləri tənzimləyə bilərsiniz:

ip_prefix: Qeydiyyat üçün icazə verilən IP prefiksi
api_prefix: Qeydiyyat ediləcək API sorğularının prefiksi

Bu parametrləri .env faylında da təyin edə bilərsiniz:
    
```bash
API_DOCS_IP_PREFIX=YOUR_IP_PREFIX
API_DOCS_API_PREFIX=api/*
```

## İstifadə
Paket quraşdırıldıqdan sonra, konfiqurasiya edilmiş IP və API prefiksinə uyğun bütün API sorğuları avtomatik olaraq qeydə alınacaq.
API sənədləşdirməsini görmək üçün:
```bash
GET /api-docs
```
Xüsusi bir API endpointi haqqında ətraflı məlumat əldə etmək üçün:
```bash
GET /api-docs/{id}
```
## Paket Strukturu
```bash
api-docs/
├── src/
│   ├── ApiDocsController.php
│   ├── ApiDocsServiceProvider.php
│   ├── config/
│   │   └── api-docs.php
│   ├── database/
│   │   └── migrations/
│   │       └── 2024_01_01_000000_create_api_docs_table.php
│   └── views/
│       ├── api_docs.blade.php
│       └── api_doc_detail.blade.php
├── composer.json
├── LICENSE
└── README.md
```
Bu README faylı, BR Technologies üçün uyğunlaşdırılmış API-Docs paketinin bütün əsas məlumatlarını əhatə edir. Paket strukturu, əsas komponentlər və istifadə təlimatları daxil edilmişdir. Siz bu faylı öz ehtiyaclarınıza və paketinizin spesifik xüsusiyyətlərinə uyğun olaraq daha da təkmilləşdirə bilərsiniz.