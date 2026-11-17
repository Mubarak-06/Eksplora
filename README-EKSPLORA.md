# EKSPLORA

Media Pembelajaran Interaktif Ekosistem Lahan Basah Kalimantan Selatan, IPA SMP/MTs kelas VII.

## Menjalankan aplikasi di XAMPP

Proyek ini menggunakan Laravel 12, PHP 8.2+, MySQL/MariaDB, Blade, CSS custom, dan JavaScript vanilla. Node.js, npm, dan Vite tidak diperlukan untuk halaman EKSPLORA.

1. Aktifkan MySQL pada XAMPP. Database lokal yang digunakan adalah `db_eksplora`.
2. Konfigurasi koneksi sudah tersedia di `.env` dan tidak diubah oleh implementasi ini. Jangan menyalin kredensial ke repository atau laporan.
3. Dari direktori proyek jalankan `php artisan serve`.
4. Buka **http://127.0.0.1:8000**. Buat akun melalui **Daftar**. Tidak ada akun dengan password bawaan.

Alternatif Apache: buka `http://localhost/eksplora/public/`, atau arahkan DocumentRoot virtual host ke `C:/xampp/htdocs/eksplora/public`. Jangan jadikan akar repository sebagai DocumentRoot karena berisi konfigurasi privat.

Migration dan seeder sudah dijalankan. Untuk instalasi baru dengan dependensi PHP tersedia:

```powershell
php artisan migrate
php artisan db:seed --class=EksploraSeeder
php artisan serve
```

Seeder memakai slug/kode unik sehingga tidak menggandakan baris. Menjalankan ulang seeder **memperbarui materi dan aturan bawaan** dengan versi dalam source; gunakan dengan sengaja jika materi telah diedit manual. Seeder tidak membuat, menghapus, atau mengubah akun dan progres pengguna. Jangan menjalankan `migrate:fresh` atau `db:wipe` pada database yang berisi data pengguna.

## Fitur

- Beranda satu viewport pada desktop 1280×800, statistik aktual, dan navbar bersama pada semua halaman.
- Peta ilustrasi interaktif lima ekosistem dan materi dasar lahan basah.
- Detail ekosistem dengan tujuh tab, contoh flora/fauna, adaptasi, dan penyelesaian materi pelestarian.
- Katalog organisme: kategori, nama umum/ilmiah, dan habitat dapat difilter bersama. Detail memakai slug dan satu sumber data yang sama.
- Perbandingan dengan tukar posisi, hasil tabel dinamis, dan validasi dua ekosistem berbeda.
- Kuis pilihan ganda, tebak ilustrasi, mencocokkan habitat, dan puzzle tukar posisi 3×3. Matching menerima semua habitat yang terhubung dengan organisme.
- Registrasi, login session, logout, edit nama, profil, progres, riwayat aktivitas dan hasil kuis.
- Delapan lencana, dua penghargaan terukur, poin, level, dan riwayat transaksi dari database.
- Pencarian global yang membuka detail ekosistem/organisme.
- Mode tamu dapat membaca dan bermain tanpa menyimpan hasil permanen.

## Aturan belajar dan keamanan

Progres baca dicatat saat pengguna menekan **Tandai Selesai**, bukan saat halaman dibuka. Tombol ini merupakan pernyataan siswa bahwa ia telah belajar, bukan bukti durasi atau pemahaman.

| Aktivitas | Poin | Batas |
| --- | ---: | --- |
| Materi dasar | 20 | Sekali |
| Satu ekosistem | 20 | Sekali per ekosistem |
| Satu organisme | 5 | Sekali per organisme |
| Perbandingan | 10 | Sekali untuk perbandingan pertama |
| Materi pelestarian | 5 | Sekali per ekosistem |
| Pilihan ganda, tebak gambar, matching | 0–20 | Total tiap jenis mengikuti hasil terbaik |
| Puzzle | 15 | Sekali; ulang bermain tetap bisa |

Satu level membutuhkan 100 XP. Nilai dasar disimpan pada `learning_rules`. Hasil kuis dihitung server dari jawaban yang diperiksa satu per satu. Puzzle diverifikasi dengan memutar ulang urutan pertukaran dari susunan awal dalam session. Token permainan dikonsumsi setelah selesai; request session permainan dikunci agar jawaban paralel tidak saling menimpa. Pengguna hanya memiliki satu permainan aktif dalam satu session browser.

Poin dan lencana memakai transaksi database, kunci baris pengguna, serta constraint unik. Browser tidak menentukan skor atau poin. Password menggunakan cast `hashed` Laravel. Semua mutasi memakai CSRF dan validasi server; data privat selalu mengambil akun yang sedang login. Endpoint masuk/daftar dan mulai kuis memiliki pembatasan request.

## Struktur database

Tabel bawaan `users`, `password_reset_tokens`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, dan `migrations` dipertahankan.

| Tabel aplikasi | Isi dan hubungan |
| --- | --- |
| `ecosystems` | Lima ekosistem; slug unik; materi JSON |
| `organisms` | Tujuh organisme; nama ilmiah, kategori, materi dan sumber |
| `ecosystem_organism` | Relasi many-to-many organisme–habitat; pasangan unik |
| `quiz_questions` | Lima soal pilihan ganda dan tujuh soal ilustrasi; pilihan, kunci, penjelasan |
| `quiz_puzzles` | Lima puzzle yang terhubung ke ekosistem |
| `quiz_attempts` | Hasil, jawaban/gerakan, token unik, dan pemilik akun |
| `user_learning_progress` | Aktivitas yang ditandai selesai; unik per pengguna, aktivitas, subjek |
| `badges` | Delapan definisi dan persyaratan lencana |
| `user_badges` | Perolehan lencana unik per pengguna dan lencana |
| `point_transactions` | Riwayat poin; sumber unik per pengguna |
| `user_awards` | Dua penghargaan; unik per pengguna dan jenis |
| `learning_rules` | Aturan poin serta XP per level |

Foreign key digunakan untuk hubungan pengguna, organisme, ekosistem, puzzle, dan lencana. Subjek progres menggunakan slug dengan validasi server karena mengacu pada beberapa jenis materi.

## File utama

- `routes/web.php` — route publik dan akun.
- `app/Http/Controllers/{Learning,Auth,Quiz}Controller.php` — halaman, validasi, dan endpoint permainan.
- `app/Services/ProgressService.php` — progres, poin, lencana, penghargaan.
- `app/Services/QuizService.php` — penilaian dan verifikasi permainan.
- `app/Models/` — model beserta relasi Eloquent.
- `database/migrations/2026_11_16_*` — skema tambahan; tabel lama dipertahankan.
- `database/seeders/EksploraSeeder.php` — seluruh materi dan aturan awal.
- `resources/views/layouts/app.blade.php`, `components/`, `pages/` — layout dan halaman bersama.
- `public/css/app.css`, `public/js/app.js`, `public/js/quiz.js` — aset tanpa build.
- `public/images/` — logo, enam bentang lahan, dan tujuh ilustrasi organisme lokal.
- `tests/Feature/LearningTest.php` — pengujian fitur, isolasi pengguna, dan aturan penilaian.
- `scripts/browser_check.py` — QA Chrome lokal dengan laporan dan screenshot.

## Pengujian

```powershell
php artisan test
```

PHPUnit memakai **SQLite dalam memori** sesuai `phpunit.xml`, bukan database MySQL lokal. Pengujian mencakup route publik, slug, katalog, pencarian, validasi perbandingan, akun, otorisasi, progres eksplisit, idempotensi, seluruh lencana/penghargaan, skor salah/benar, semua habitat matching, replay token, dan puzzle.

Browser QA menggunakan Chrome headless yang sudah terpasang, Python, serta `websocket-client` yang sudah tersedia. Tidak memerlukan Node.js. Jalankan `python scripts/browser_check.py` jika ingin mengulang. Skrip memulai server PHP sementara di port 8123, Chrome di port 9229, memakai profil browser sementara, lalu menghentikan keduanya. Pastikan port tersebut bebas. Laporan dan screenshot ada di `storage/app/browser-check/`. Permainan diuji sebagai tamu sehingga tidak membuat akun/progres permanen.

## Aset dan batas implementasi

Semua kontrol dibuat dengan HTML/CSS/JavaScript; gambar referensi tidak dipasang sebagai antarmuka utuh. Gaya warna, kartu, navbar, komposisi hero, peta, tabel, dan koleksi mengikuti referensi, tetapi **ilustrasi SVG lokal lebih sederhana** daripada gambar referensi. Logo memakai SVG dan avatar memakai inisial akun.

Tidak ada aset eksternal yang wajib diunduh untuk menjalankan aplikasi. Untuk hasil visual yang lebih dekat ke referensi, siapkan ilustrasi habitat dan gambar organisme yang akurat serta berizin untuk mengganti file di `public/images/`. Gambar tebak organisme adalah ilustrasi pembelajaran, bukan foto identifikasi. Generator opsional: `php scripts/build_assets.php` (akan menimpa SVG ilustrasi bawaan, jangan dipakai setelah mengganti aset secara manual).

Sumber dan catatan materi ada di `SUMBER-MATERI.md`. Cakupan materi bukan daftar lengkap spesies Kalimantan Selatan. Kecocokan habitat tidak menyatakan keberadaan di setiap lokasi, dan tidak ada status konservasi yang diklaim tanpa verifikasi.

PHP XAMPP saat pengerjaan menampilkan peringatan startup `iconv` serta duplikasi `openssl`, `intl`, dan `sodium`. PHP tetap memiliki modul tersebut dan aplikasi/tes dapat berjalan. Konfigurasi global `php.ini` tidak diubah. `artisan db:show` juga menemui tabel diagnostik `performance_schema.session_status` yang tidak tersedia pada MariaDB lokal; migration, seeding, query aplikasi, dan browser berjalan melalui MySQL.
