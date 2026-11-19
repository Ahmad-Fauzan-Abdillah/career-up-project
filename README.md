# CareerUp

CareerUp adalah platform karier untuk mahasiswa dan fresh graduate yang menyediakan lowongan, magang, pelatihan, lamaran, langganan premium, serta fitur akun. Aplikasi menggunakan Laravel 12, Blade, Tailwind CSS, dan Vite.

## Persyaratan

- PHP 8.2+ dengan ekstensi Laravel/PDO untuk MySQL atau SQLite, `fileinfo`, `mbstring`, `openssl`, `tokenizer`, `xml`, dan `ctype`.
- Composer 2.
- Node.js 20+ dan npm.
- MySQL 8+ atau MariaDB yang kompatibel (SQLite dapat dipakai untuk pengembangan).

## Menjalankan lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Di PowerShell Windows, gunakan `Copy-Item .env.example .env` sebagai pengganti `cp`.

Atur `APP_ENV=local`, `APP_DEBUG=true`, `APP_URL=http://127.0.0.1:8000`, koneksi database lokal, dan mailer lokal pada `.env`. Untuk SQLite, buat `database/database.sqlite` dan set `DB_CONNECTION=sqlite` serta `DB_DATABASE=database/database.sqlite`.

Siapkan admin pertama dengan mengisi `ADMIN_NAME`, `ADMIN_EMAIL`, dan `ADMIN_PASSWORD` di `.env`, lalu jalankan:

```bash
php artisan migrate --seed
php artisan storage:link
npm ci
npm run build
```

Jalankan server pengembangan:

```bash
php artisan serve
npm run dev
```

Buka `http://127.0.0.1:8000`. Jalankan pengujian dengan `php artisan test`.

`php artisan migrate --seed` membuat atau memperbarui satu admin dari tiga variabel `ADMIN_*`. Jangan memakai kata sandi contoh atau mengaktifkan `ALLOW_ADMIN_REGISTRATION` pada produksi. Akun admin selanjutnya dibuat dengan prosedur operasional yang aman dan tidak melalui pendaftaran publik.

Data contoh lama tersedia melalui `DemoSeeder` dan hanya dapat dijalankan secara manual saat `APP_ENV=local`:

```bash
php artisan db:seed --class=DemoSeeder
```

## Variabel lingkungan

`.env.example` memuat nilai contoh dengan `APP_ENV=production`, `APP_DEBUG=false`, dan semua kelompok variabel aplikasi. Salin ke `.env` privat di host dan ganti nilai contoh sebelum menjalankan aplikasi.

| Kelompok | Variabel | Keterangan |
|---|---|---|
| Aplikasi | `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_DEBUG`, `APP_TIMEZONE`, `APP_URL`, `APP_LOCALE`, `APP_FALLBACK_LOCALE`, `APP_FAKER_LOCALE`, `APP_MAINTENANCE_DRIVER` | Identitas, URL publik HTTPS, key enkripsi, mode/debug, zona waktu, bahasa, dan maintenance. Isi `APP_KEY` memakai `php artisan key:generate`; produksi wajib `APP_DEBUG=false`. |
| Admin | `ADMIN_NAME`, `ADMIN_EMAIL`, `ADMIN_PASSWORD`, `ALLOW_ADMIN_REGISTRATION` | Kredensial bootstrap admin; jangan commit password dan biarkan pendaftaran admin publik nonaktif. |
| Database | `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_CHARSET`, `DB_COLLATION` | Koneksi MySQL/MariaDB atau SQLite. Gunakan user database khusus aplikasi, bukan `root`. |
| Mail | `MAIL_MAILER`, `MAIL_SCHEME`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME` | SMTP untuk verifikasi email dan reset password. Gunakan secret SMTP dari secret manager/konfigurasi host. |
| Session/cache | `SESSION_DRIVER`, `SESSION_LIFETIME`, `SESSION_ENCRYPT`, `SESSION_PATH`, `SESSION_DOMAIN`, `SESSION_SECURE_COOKIE`, `SESSION_HTTP_ONLY`, `SESSION_SAME_SITE`, `CACHE_STORE` | Produksi memakai cookie secure lewat HTTPS; database menjadi default session/cache. |
| Queue/log | `QUEUE_CONNECTION`, `LOG_CHANNEL`, `LOG_LEVEL`, `LOG_DAILY_DAYS` | Queue default `database`; logging default `daily`. |
| Pembayaran | `PAYMENT_QRIS_IMAGE`, `PAYMENT_MONTHLY_PRICE`, `PAYMENT_ANNUAL_PRICE`, `PAYMENT_UNIQUE_CODE_MIN`, `PAYMENT_UNIQUE_CODE_MAX`, `PAYMENT_INSTRUCTIONS` | Konfigurasi pembayaran manual QRIS; payment gateway belum terhubung. |
| File/opsional | `FILESYSTEM_DISK`, `REDIS_CLIENT`, `REDIS_HOST`, `REDIS_PASSWORD`, `REDIS_PORT`, `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_DEFAULT_REGION`, `AWS_BUCKET`, `AWS_USE_PATH_STYLE_ENDPOINT`, `VITE_APP_NAME` | Disk default lokal; Redis dan S3 hanya diperlukan jika dipilih. Jangan menyimpan secret di variabel `VITE_*`. |

## Deploy VPS (Nginx + PHP-FPM + MySQL)

Contoh berikut memakai Ubuntu, domain yang sudah mengarah ke IP server, PHP-FPM 8.3, Nginx, MySQL, Composer, Node.js/npm, Supervisor, dan Certbot.

1. Pasang PHP dan ekstensi yang diperlukan Laravel/PDO MySQL, Nginx, MySQL, Composer, Node.js/npm, Supervisor, dan Certbot.
2. Letakkan source code di `/var/www/careerup` di luar document root lain. Instal dependency:

   ```bash
   cd /var/www/careerup
   composer install --no-dev --optimize-autoloader --no-interaction
   npm ci
   npm run build
   cp .env.example .env
   ```

3. Buat database dan akun MySQL khusus aplikasi. Isi `.env` produksi: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://domain-anda`, kredensial database, SMTP, `QUEUE_CONNECTION=database`, dan `SESSION_SECURE_COOKIE=true`. Simpan `.env` hanya di root aplikasi.
4. Siapkan key, database, storage, dan cache:

   ```bash
   php artisan key:generate
   php artisan migrate --force
   php artisan db:seed --force
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan event:cache
   ```

   Pastikan `ADMIN_NAME`, `ADMIN_EMAIL`, dan `ADMIN_PASSWORD` berisi secret sebelum menjalankan seeder.
5. Atur ownership/permission supaya user PHP-FPM dapat menulis hanya ke `storage/` dan `bootstrap/cache/`. Arahkan Nginx `root` ke `/var/www/careerup/public`; jangan arahkan web ke root repository. Pastikan PHP-FPM memakai `public/index.php`.
6. Aktifkan HTTPS, lalu:

   ```bash
   sudo certbot --nginx -d domain-anda
   ```

   Nginx harus mengarahkan HTTP ke HTTPS. `APP_URL` dan cookie sesi secure juga harus menggunakan HTTPS.
7. Jalankan queue worker melalui Supervisor. Simpan konfigurasi, misalnya `/etc/supervisor/conf.d/careerup-worker.conf`:

   ```ini
   [program:careerup-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /var/www/careerup/artisan queue:work database --sleep=3 --tries=3 --timeout=90
   directory=/var/www/careerup
   user=www-data
   numprocs=1
   autostart=true
   autorestart=true
   stopasgroup=true
   killasgroup=true
   redirect_stderr=true
   stdout_logfile=/var/www/careerup/storage/logs/worker.log
   stopwaitsecs=3600
   ```

   Muat konfigurasi dengan `sudo supervisorctl reread`, `sudo supervisorctl update`, dan `sudo supervisorctl start careerup-worker:*`. Sesuaikan user/path dengan server.
8. Daftarkan scheduler cron untuk user aplikasi:

   ```cron
   * * * * * cd /var/www/careerup && php artisan schedule:run >> /dev/null 2>&1
   ```

   Job pengingat pelatihan dan kedaluwarsa langganan dijadwalkan di `routes/console.php`.

## Deploy shared hosting cPanel

Pastikan hosting menyediakan PHP 8.2+, ekstensi Laravel yang dibutuhkan, Composer/Terminal, MySQL, cron, dan akses HTTPS.

1. Unggah repository ke direktori **di luar** `public_html`, misalnya `/home/USER/careerup`. Jangan menaruh `.env`, `vendor`, atau source aplikasi yang sensitif di direktori publik.
2. Instal dependency dari Terminal cPanel:

   ```bash
   cd /home/USER/careerup
   composer install --no-dev --optimize-autoloader --no-interaction
   npm ci
   npm run build
   cp .env.example .env
   ```

   Jika Node/npm tidak tersedia di hosting, jalankan `npm ci && npm run build` secara lokal/CI dan unggah hasil `public/build`.
3. Di cPanel buat database dan user MySQL, lalu hubungkan user ke database dengan hak yang diperlukan. Isi `.env` dengan kredensial tersebut, SMTP, domain HTTPS, `APP_ENV=production`, `APP_DEBUG=false`, `QUEUE_CONNECTION=database`, dan `SESSION_SECURE_COOKIE=true`. Jalankan `php artisan key:generate`.
4. Jadikan isi direktori `careerup/public` sebagai document root domain. Jika domain cPanel tidak bisa diarahkan ke subfolder `public`, salin **isi** folder itu ke `public_html`, lalu ubah path di `public_html/index.php` agar mengarah ke `/home/USER/careerup/vendor/autoload.php` dan `/home/USER/careerup/bootstrap/app.php`. Pastikan `.env` dan kode aplikasi tetap di luar `public_html`.
5. Dari Terminal jalankan `php artisan migrate --force`, `php artisan db:seed --force` (setelah secret admin diatur), dan perintah cache di bawah. Pastikan permission `storage/` dan `bootstrap/cache/` dapat ditulis oleh PHP.
6. Jalankan `php artisan storage:link` dari direktori aplikasi jika document root memakai `careerup/public`. Jika isi `public` disalin ke `public_html`, pastikan `public_html/storage` mengarah ke `/home/USER/careerup/storage/app/public` (buat symlink di folder publik itu atau minta bantuan hosting). Jangan memindahkan CV atau bukti pembayaran ke direktori publik.
7. Buat dua cron job di cPanel. Sesuaikan absolute path PHP dan direktori aplikasi dari penyedia hosting:

   ```cron
   * * * * * cd /home/USER/careerup && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1
   * * * * * cd /home/USER/careerup && /usr/local/bin/php artisan queue:work database --stop-when-empty --tries=3 --timeout=90 >> /dev/null 2>&1
   ```

   Cron kedua memproses antrean berkala pada shared hosting; untuk worker persisten gunakan VPS/Supervisor.
8. Aktifkan SSL/AutoSSL di cPanel dan pastikan domain memakai HTTPS.

## Perintah optimasi dan operasional

Jalankan setelah setiap rilis (setelah dependency dan environment siap):

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

Setelah mengubah konfigurasi/rute pada mesin yang sama, bersihkan cache yang sesuai (`php artisan optimize:clear`) sebelum membuat ulang cache produksi.

Queue memakai tabel `jobs` dan `failed_jobs` yang disiapkan migrasi Laravel. Operasional:

```bash
php artisan queue:work database --tries=3 --timeout=90
php artisan queue:failed
php artisan queue:retry all
php artisan schedule:run
```

Cron scheduler dijalankan setiap menit; jangan menjalankan `schedule:run` hanya sekali saat deploy.

## Backup dan pemulihan

- **MySQL:** backup konsisten memakai `mysqldump` dan simpan di lokasi terenkripsi di luar web root. Contoh: `mysqldump --single-transaction -u USER -p DATABASE > careerup-$(date +%F).sql`. Uji pemulihan secara berkala ke database staging.
- **Storage:** cadangkan seluruh `storage/app/` (termasuk `private/` untuk CV dan bukti pembayaran) ke penyimpanan terenkripsi dan akses terbatas. Contoh: `tar -czf careerup-storage-$(date +%F).tar.gz -C /var/www/careerup storage/app`.
- Sertakan `.env` dan `APP_KEY` ke backup rahasia yang aksesnya dibatasi; key yang hilang membuat data terenkripsi lama tidak dapat dibaca. Jangan masukkan backup atau file upload ke Git atau web root.

## Aset dan batasan produk

Gambar aplikasi disimpan lokal di `public/images/`. Ganti placeholder dengan materi final: `avatar-default.png`, `banner-beranda.jpg`, `landing-hero.jpg`, `landing-internship.jpg`, `landing-training.jpg`, `logo.png`, dan `qris-placeholder.svg`. Logo perusahaan, avatar mentor, dan thumbnail pelatihan dapat diunggah lewat admin. CV dan bukti pembayaran disimpan di disk privat dan hanya dapat diunduh melalui rute berotorisasi.

Pembayaran QRIS masih berupa proses manual dan belum terhubung ke payment gateway.

## Checklist rilis

- [ ] Domain DNS diarahkan ke host dan `APP_URL` memakai HTTPS yang benar.
- [ ] `.env` produksi dibuat di luar web root; `APP_KEY`, kredensial database, password admin, SMTP, dan secret lain diisi secara rahasia.
- [ ] `APP_ENV=production`, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`, `LOG_CHANNEL=daily`, dan `QUEUE_CONNECTION=database`.
- [ ] Database dan file `storage/app` memiliki backup terkini; pemulihan pernah diuji.
- [ ] Migrasi dijalankan (`php artisan migrate --force`); storage dan cache writable dengan permission minimum.
- [ ] Asset frontend dibangun (`npm ci && npm run build`); favicon/brand final tersedia.
- [ ] HTTPS/Certbot atau AutoSSL aktif dan redirect HTTP ke HTTPS sudah dicek.
- [ ] Supervisor worker atau cron queue aktif; scheduler cron setiap menit aktif.
- [ ] `php artisan test`, `npm run build`, dan cache config/route/view/event berjalan sukses.
- [ ] Endpoint `/up`, login, reset/verifikasi email, unggahan privat, sitemap, serta halaman error diuji di staging.
- [ ] Kredensial bootstrap admin tidak tersimpan di Git/log; admin pertama dapat login dan mengganti password awal.
