# Edo Odyssey - Edo State Tourism Agency Portal

A Laravel 12 CMS for the Edo State Tourism Agency (EDSTA), built around the "Beyond the Bronzes" mission to celebrate the history, heritage, and culture of Edo State, Nigeria.

## Features

- Full admin CMS for attractions, guides, hotels, restaurants, events, news, categories, FAQs, and partners
- Edo Culture Hub at /culture: indigenous languages, traditional cuisine, and ethnic attire
- Bulk upload (Excel/CSV) for 10 content types with template download
- Rich attraction detail pages showing extended history, unique selling points, ownership, investment opportunities, visiting tips, landmarks, and proximity
- 12 featured attractions on home page carousel
- Review system with ratings, favourites/wishlist, and QR-code scanning

## Current Content

| Content Type | Records |
|---|---|
| Attractions | 88 |
| Edo Languages | 9 |
| Traditional Cuisine | 17 |
| Ethnic Attire | 10 |
| Categories | 8 |

## Tech Stack

- Laravel 12.63
- PHP 8.5
- SQLite (dev) / PostgreSQL-compatible (prod)
- Bootstrap 5, Bootstrap Icons
- Maatwebsite/Excel 3.x for imports

## Quick Start

```r
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```r

Then open http://127.0.0.1:8000

## Admin Access

- Super Admin: superadmin@edoodyssey.ng
- Admin panel: http://127.0.0.1:8000/admin
- One-click demo logins available on the home page

## Credits

Original project by @Franie83. Enhancement work delivered: mojibake repair across source files and DB, 7 new migrations (5 new content tables + 2 extended), 5 new admin CRUDs with bulk upload, extended Attraction and Hotel admin forms, public Culture page, 88 enriched attractions, 36 culture records, and final house cleaning.

## License

Proprietary - Edo State Tourism Agency (EDSTA), 2026.

