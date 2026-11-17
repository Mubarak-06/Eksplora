# Pengujian UI EKSPLORA - 17 November 2026

Perubahan pada Blade, CSS custom, dan enam ilustrasi WebP lokal (1586 x 992). Seluruh komponen UI tetap HTML interaktif. Statistik tetap memakai variabel database yang sama. Ekosistem memakai ilustrasi berdasarkan slug dengan fallback aset lama.

- Laravel: 18 tes lulus, 189 assertions; SQLite testing terpisah.
- Browser Chrome: 84 pemeriksaan lulus, tanpa error JavaScript.
- Pemeriksaan tambahan: 18 lulus; lebar 320, 600, 901, 1024, 1440 piksel serta pemuatan WebP pada kelima halaman ekosistem.
- Beranda 1280 x 800 tanpa scroll vertikal/horizontal; navbar, CTA, dan statistik terlihat utuh.
- Responsif utama: 1280 x 800, 768 x 1024, 390 x 844.
- Pencarian, menu mobile, tab materi, perbandingan, dan empat jenis kuis berhasil.

Screenshot: storage/app/browser-check/home-desktop.png, home-mobile.png, eksplorasi.png, flora-fauna.png, kuis.png, ecosystem-nature-desktop.png.

Prompt dan asal aset: public/images/ILLUSTRATION-SOURCES.md. Ilustrasi dibuat menggunakan built-in image_gen; tidak memakai screenshot sebagai latar. SVG lama tetap tersedia untuk kuis dan fallback.

Lingkungan: MySQL XAMPP dinyalakan untuk pengujian browser. PHP CLI memiliki peringatan konfigurasi iconv serta modul openssl/intl/sodium ganda; tes tetap lulus. Konfigurasi PHP dan .env tidak diubah.
