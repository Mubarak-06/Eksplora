# Hasil pengujian EKSPLORA

Tanggal: 16 November 2026, WITA. Lingkungan: Windows/XAMPP, PHP 8.2.12, Laravel 12.72.0, MySQL/MariaDB lokal.

## Backend

- Migration tambahan berhasil diterapkan tanpa menghapus tabel/data lama.
- Seeder berhasil pada `db_eksplora`: 5 ekosistem, 7 organisme, 12 pertanyaan, 5 puzzle, 8 lencana, dan 8 aturan poin/level.
- Seluruh 22 route aplikasi terdaftar. Halaman publik, 5 detail ekosistem, 7 detail organisme, dan 4 halaman permainan diuji; slug yang tidak ada memberikan 404.
- `php artisan view:cache` berhasil mengompilasi Blade.
- Laravel Pint berhasil memformat source PHP.
- `php artisan test`: **18 tests passed, 189 assertions**.

Tes fitur menggunakan SQLite dalam memori. Koneksi MySQL, penerapan skema, seed, dan halaman browser menggunakan database lokal. Tes SQLite bukan uji race condition lintas koneksi MySQL; implementasi pencegah duplikasi memakai transaksi, kunci baris pengguna, constraint unik, dan penguncian session permainan.

Cakupan otomatis: registrasi, hash password, login salah/benar, logout, edit nama, isolasi akun, redirect tamu, filter kombinasi, pencarian, perbandingan, penyelesaian baca eksplisit, poin hanya sekali, lencana sesuai aktivitas, seluruh penghargaan, skor benar/salah, jawaban berurutan, kunci tidak terkirim saat mulai, penolakan permainan belum selesai, replay token, semua habitat matching, dan verifikasi urutan puzzle.

## Browser Chrome headless

`python scripts/browser_check.py`: **84 checks passed, 0 failed, 0 JavaScript errors**.

- Beranda pada 1280×800: tinggi dokumen tidak melebihi viewport. Tombol Mulai Jelajah dan panel statistik berada dalam viewport.
- Halaman Beranda, Eksplorasi, Tentang Lahan Basah, detail Rawa Lebak, katalog, detail Haruan, Perbandingan, Kuis, dan Masuk diuji pada **1280×800**, **768×1024**, dan **390×844**. Tidak ditemukan scroll horizontal atau gambar utama gagal dimuat.
- Pencarian navbar dibuka dan dipakai mencari Haruan.
- Tab flora/pelestarian mengganti konten detail ekosistem.
- Tombol tukar dan submit perbandingan memperbarui pilihan/hasil.
- Navbar mobile menampilkan tujuh tautan navigasi.
- Lima jawaban dikirim lewat UI untuk masing-masing pilihan ganda, tebak gambar, dan matching. Umpan balik tampil dan nilai akhir berhasil dihitung server.
- Puzzle disusun lewat klik pertukaran potongan sampai selesai, lalu server mengembalikan nilai 100.

Artefak lokal:

- `storage/app/browser-check/report.json`
- `storage/app/browser-check/home-desktop.png`
- `storage/app/browser-check/home-mobile.png`
- `storage/app/browser-check/eksplorasi.png`
- `storage/app/browser-check/flora-fauna.png`
- `storage/app/browser-check/kuis.png`

Screenshot desktop/mobile telah diperiksa secara visual dan dibandingkan dengan komposisi referensi. Palet, navbar, kartu, posisi hero, dan struktur halaman mengikuti arah desain; ilustrasi SVG lebih sederhana dan belum setara detail raster referensi. Pengujian browser akun login/profil/koleksi dilakukan melalui tes HTTP Laravel, bukan alur UI Chrome. Browser lain dan perangkat fisik belum diuji.

## Catatan lingkungan

Peringatan startup PHP tentang `iconv` dan ekstensi ganda masih muncul. Konfigurasi global XAMPP tidak diubah. Perintah diagnostik `db:show` menemui tabel `performance_schema.session_status` yang tidak tersedia; koneksi/query aplikasi, migration, seeding, dan QA browser tetap berhasil. Tidak ada `migrate:fresh`, `db:wipe`, atau penghapusan data pengguna yang dijalankan.
