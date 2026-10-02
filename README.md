# codewithronn — Portfolio Roni Zeki

Portfolio Laravel 12, Blade, Tailwind CSS 4, Vite, dan AOS. Desain responsive dengan CTA WhatsApp, publikasi studi kasus terverifikasi, contact form server-side, dan technical SEO. Font Inter disajikan dari aset lokal. Tidak ada CDN frontend atau database yang wajib untuk alur portfolio.

## Requirements

- PHP 8.2+ dengan ekstensi Laravel: ctype, curl, dom, fileinfo, filter, hash, mbstring, openssl, pcre, PDO, session, tokenizer, xml. Lockfile ditargetkan ke PHP 8.2.
- Composer 2, Node.js 22 LTS atau versi kompatibel, npm.
- Web server PHP (Nginx/Apache) untuk production dan SMTP untuk email sungguhan.

## Installation

```sh
composer install
cp .env.example .env
php artisan key:generate
npm ci
npm run build
php artisan serve --host=127.0.0.1 --port=8000
```

Windows PowerShell: ganti `cp .env.example .env` dengan `Copy-Item .env.example .env`. Jangan menimpa `.env` yang sudah dikonfigurasi. Buka http://127.0.0.1:8000. Untuk perubahan frontend dengan hot reload, jalankan `npm run dev` di terminal kedua. Hapus proses Vite development sebelum menggunakan hasil production build agar `public/hot` tidak menunjuk server development yang sudah mati.

Default menggunakan `SESSION_DRIVER=file`, `CACHE_STORE=file`, dan `QUEUE_CONNECTION=sync`. Database tidak diperlukan untuk portfolio atau form. Pesan tidak disimpan ke tabel database; Laravel Mail mengirim secara synchronous. Model dan migration bawaan Laravel tersedia jika nanti diperlukan. Jika menggunakan session/cache database: konfigurasi DB, buat database terlebih dahulu, kemudian jalankan `php artisan migrate`. Gunakan Redis/shared cache dan shared session store pada deployment multi-instance supaya rate limit tetap konsisten.

## Project structure

```text
app/Http/Controllers/PortfolioController.php   Homepage, studi kasus, sitemap, robots
app/Http/Controllers/ContactController.php     Pengiriman dan kegagalan email
app/Http/Requests/ContactRequest.php           Validasi server-side dan honeypot
app/Http/Middleware/SecurityHeaders.php        Header keamanan dasar
app/Mail/ProjectInquiry.php                   Mailable dengan reply-to pengunjung
app/Support/Portfolio.php                     Filter demo, URL canonical, WhatsApp
config/portfolio.php                         Semua konten yang sering berubah
config/analytics.php                         ID analytics dan verifikasi
resources/views/home.blade.php               Komposisi homepage
resources/views/components/                  Komponen Blade reusable
resources/views/projects/show.blade.php       Halaman studi kasus
resources/views/mail/                        Email HTML dan plain text
resources/views/errors/                      Halaman 404, 419, 429
resources/views/seo/sitemap.blade.php          XML sitemap
resources/css/portfolio.css                  Sistem visual dan breakpoint
resources/css/app.css                        Entry Tailwind
resources/js/app.js                          AOS, menu, filter, tracking
public/images/                              WebP, varian kecil, OG
routes/web.php                              Rute publik dan contact POST
tests/Feature/PortfolioTest.php              Pengujian fitur
```

## Editing content and images

Edit `config/portfolio.php`: identitas, social links, WhatsApp, skills, layanan, masalah, proses, budget, klien, proyek, testimonial. Teks editorial panjang berada di homepage/komponen Blade. Ubah pengalaman melalui konfigurasi dan sesuaikan kalimat editorial jika angka pengalaman berubah.

Foto asli belum diberikan, sehingga website menggunakan monogram identitas CWR tanpa foto placeholder. Untuk menampilkan foto Anda, simpan file WebP di public/images/ lalu isi key profile di config/portfolio.php dengan path tersebut. Social preview menggunakan public/images/og.webp dan favicon public/favicon.svg.

### Publikasi data nyata

Seluruh klien, testimonial, dan angka statistik fiktif telah dihapus. Enam layout proyek yang dapat diedit tersedia di config/portfolio.php atas permintaan pemilik; teksnya berupa instruksi pengisian, bukan klaim karya nyata. Gambar konsep lama juga dihapus dari public. Bagian proyek tampil dengan kartu dan halaman detail. Bagian klien/testimonial tetap tersembunyi jika datanya kosong. Pengalaman 6+ tahun dan informasi kontak berasal dari brief pemilik.

Tambahkan data nyata dengan izin pemilik/klien ke array clients, projects, atau testimonials. Setiap entri wajib memiliki published => true agar tampil; entri draft atau yang memiliki demo => true selalu disembunyikan, termasuk di local. Jangan mengubah entri fiktif menjadi published. Proyek menggunakan field slug, title, client, category, description, problem, solution, outcome, features (array), tech (array), image (path WebP), dan published. Klien menggunakan name, mark, published. Testimonial menggunakan quote, name, role, client, published. Jangan membuat klaim outcome tanpa bukti.

Domain production belum diberikan: APP_URL tetap localhost dan indexing tetap dinonaktifkan sampai domain final dikonfigurasi. Ini bukan deployment publik.

## Contact form and mail

Default `CONTACT_FORM_ENABLED=true`: formulir selalu tampil, termasuk sebelum SMTP siap. Set false jika ingin menyembunyikannya dan menonaktifkan endpoint. Mailer log/array hanya mencatat pesan secara lokal; production menolak pengiriman dengan mailer tersebut. Konfigurasikan SMTP sebelum menerima inquiry production. Default `MAIL_MAILER=log` hanya menulis email ke log development jika form sengaja diaktifkan untuk pengujian. UI menyatakan secara eksplisit bahwa email tidak dikirim. Mode production menolak mailer `log` dan `array` untuk mencegah laporan keberhasilan palsu.

Contoh konfigurasi SMTP (isi di `.env`, jangan commit credentials):

```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-password
MAIL_FROM_ADDRESS=hello@your-domain.example
MAIL_FROM_NAME="Roni Zeki"
CONTACT_TO_ADDRESS=ronizeki83@gmail.com
```

Ikuti pengaturan provider: port 587 biasanya menggunakan `smtp` dengan STARTTLS, atau `smtps` untuk port 465. Gunakan sender domain terverifikasi beserta SPF/DKIM. Pengunjung disetel sebagai `Reply-To`, tidak sebagai pengirim. Konfigurasi mengikuti [dokumentasi Laravel Mail](https://laravel.com/docs/12.x/mail).

Setelah konfigurasi, jalankan `php artisan config:clear` saat development atau `php artisan config:cache` di production. Uji dengan satu inquiry nyata melalui form dan periksa inbox serta log provider; pengujian otomatis menggunakan Mail fake, tidak mengirim email ke orang lain. Keberhasilan berarti transport menerima pesan, bukan jaminan masuk inbox. Jika transport gagal, form menampilkan pesan error dan mempertahankan input.

Form mencakup CSRF, required fields, batas panjang, email RFC, pilihan allowlist, honeypot, dan throttle 5 kiriman per 10 menit per IP. Penolakan rate limit/CSRF memiliki halaman 429/419 sendiri. Tidak ada detail form yang dikirim ke analytics. Log transport hanya menyimpan jenis exception; mode log lokal memuat isi email untuk debugging, jadi perlakukan log tersebut sebagai data pribadi, jangan publikasikan, dan atur retensi.

## SEO and analytics

- Satu H1 per halaman, heading bertingkat, semantic landmarks, canonical dari `APP_URL`.
- Dynamic title/description, Open Graph, Twitter Card, gambar sharing lokal.
- Person + WebSite JSON-LD pada homepage; BreadcrumbList pada studi kasus. Tidak ada rating/review fiktif.
- `/sitemap.xml` berisi homepage dan proyek non-demo. `/robots.txt` berasal dari controller, bukan file statis.
- Default `PORTFOLIO_INDEXABLE=false` memblokir indexing. Hanya production + flag true yang mengizinkan indexing.
- Isi `GOOGLE_SITE_VERIFICATION` untuk Search Console.
- Isi `GOOGLE_ANALYTICS_ID=G-...` ATAU `GOOGLE_TAG_MANAGER_ID=GTM-...`. GTM diprioritaskan agar tidak double tracking. Tidak ada tracker eksternal yang dimuat sebelum ID disetel.
- Events: `whatsapp_clicked`, `email_clicked`, `linkedin_clicked`, `instagram_clicked`, `project_viewed`, `contact_form_submitted`. Event submit hanya setelah transport nyata berhasil; mode demo tidak mencatat conversion sukses. Saat menggunakan GTM, buat Custom Event triggers dan GA4 Event tags dengan nama tersebut. `project_viewed` dipicu dari klik tautan studi kasus.
- Terapkan consent management yang sesuai kebutuhan situs sebelum mengaktifkan analytics.

Meta description Indonesia mengikuti brief; copy UI utama menggunakan Inggris. Sesuaikan keduanya jika beralih bahasa, termasuk atribut `lang` pada layout. SEO tidak menjamin ranking.

## Production deployment checklist

1. Gunakan host yang mendukung PHP/Laravel; arahkan document root HANYA ke `public/`, bukan root project. Jangan memakai `artisan serve` untuk production.
2. Pasang dependencies: `composer install --no-dev --optimize-autoloader` dan `npm ci && npm run build` di build machine. Deploy folder `public/build` bersama aplikasi. Lockfile Composer dan npm harus ikut version control.
3. Siapkan `.env` dengan `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://your-real-domain.example`, APP_KEY permanen, `SESSION_SECURE_COOKIE=true`, serta SMTP sebenarnya. Jangan mengganti APP_KEY setiap deploy.
4. Pastikan `storage/` dan `bootstrap/cache/` dapat ditulis oleh user PHP. Jangan memberikan permission 777. File `.env`, log, config, dan vendor harus tidak dapat diakses melalui web.
5. Masukkan hanya data nyata yang sudah disetujui untuk publikasi. Foto bersifat opsional karena monogram dapat digunakan di production. Periksa izin penggunaan testimonial dan screenshot.
6. Jalankan `php artisan optimize`. Untuk deployment dengan database/session database, jalankan migration yang relevan. Portfolio default tidak membutuhkannya.
7. Aktifkan HTTPS. Jika di belakang reverse proxy, konfigurasi trusted proxies secara spesifik agar URL/secure cookies benar; jangan percaya sembarang forwarded header. Pertimbangkan HSTS setelah HTTPS terverifikasi.
8. Konfigurasi server agar `/robots.txt` dan `/sitemap.xml` diteruskan ke Laravel. Gunakan rewrite Laravel standar dan blokir dotfiles (kecuali `.well-known` yang dibutuhkan).
9. Beri cache `public, max-age=31536000, immutable` untuk `/build/assets/` yang memakai hash; gunakan TTL lebih singkat untuk `/images/` karena nama mudah diganti. Jangan cache HTML/form/session secara publik. Aktifkan Brotli/gzip jika tersedia.
10. Uji seluruh CTA, form nyata dan delivery email, error states, keyboard navigation, mobile/tablet/desktop. Periksa tanpa JavaScript: konten dan navigation tetap tersedia, form tetap memakai POST standar.
11. Sesudah domain dan konten siap: `PORTFOLIO_INDEXABLE=true`, refresh config cache, cek canonical/robots/sitemap, lalu submit sitemap ke Search Console.
12. Ukur Lighthouse/PageSpeed dan Core Web Vitals di hosting nyata. Target performa bukan jaminan skor; hasil bergantung pada hosting dan gambar final.

## Quality checks

```sh
php artisan test
php artisan view:cache
php artisan route:cache
npm run build
composer validate --strict
```

Pengujian fitur mencakup halaman/studi kasus, schema, sitemap/robots, published-content filtering, validasi/honeypot, CSRF nyata, rate limit, email escaping/reply-to, transport failure, dan ketepatan pesan demo. AOS menghormati reduced motion; konten tetap terlihat tanpa JavaScript. Menu mobile menggunakan `aria-expanded`, Escape, dan navigasi keyboard. Filter proyek memberi status screen reader.

Preview lokal bukan publikasi production. Konfigurasi domain dan SMTP serta data karya asli jika ingin menampilkan portfolio diperlukan sebelum peluncuran.


## Mengedit layout proyek

Edit enam entri `projects` di `config/portfolio.php`: title, slug, client, category, description, problem, solution, outcome, features, tech, dan image. Jika image null, tampil ilustrasi layout netral. Untuk screenshot asli, letakkan WebP di public/images/projects/ dan isi path image. Set `placeholder => false` setelah konten nyata selesai agar studi kasus dapat masuk sitemap dan mengikuti pengaturan indexing situs. `published => false` menyembunyikan entri. Layout placeholder tetap terlihat agar dapat Anda edit, tetapi detailnya noindex dan dikecualikan dari sitemap.
