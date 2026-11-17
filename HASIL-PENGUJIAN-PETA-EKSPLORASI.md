# Pengujian peta Eksplorasi - 17 November 2026

Aset khusus: public/images/exploration-wetland-map.webp, 1672 x 941 piksel, 847998 byte. Dibuat dengan built-in image_gen memakai gambar pengguna sebagai referensi. Prompt, posisi pin, dan deskripsi habitat tersedia di public/images/EXPLORATION-MAP.md.

- 18 tes Laravel lulus (189 assertions), menggunakan SQLite testing terpisah.
- 61 pemeriksaan Chrome lulus tanpa exception JavaScript.
- Diuji pada 1280 x 800, 1024 x 768, 768 x 1024, 600 x 800, 390 x 844, dan 320 x 740.
- Rasio ilustrasi utuh; tidak ada pemotongan cover atau scroll horizontal.
- Pusat penanda diukur terhadap gambar: posisi relatif tetap sama pada keenam ukuran.
- Kelima penanda dapat diklik pada desktop dan mobile; tujuan halaman detail sesuai slug.
- Area klik minimal 44 x 44 piksel. Navigasi keyboard Enter berfungsi.
- Kelima nama dan ciri habitat terbaca pada legenda mobile.
- Navbar, menu aktif Eksplorasi, panel Tentang Lahan Basah, dan tombolnya dipertahankan.
- Encoding ikon diperiksa ulang setelah perbaikan screenshot.

Pemeriksaan visual: peta memperlihatkan air gambut gelap di kiri atas; akar tunjang mangrove pada lumpur muara di kanan atas; jaringan alur pasang surut di tengah; rawa lebak berumput dan berteratai di bawah tengah; sungai utama biru terbuka di kanan. Penanda ditempatkan pada masing-masing area tersebut. Gambar tidak berisi teks/pin/UI.

Screenshot final: storage/app/browser-check/exploration-map/exploration-1280-full.png, exploration-390-full.png, map-desktop.png. Audit koordinat dan hasil pemeriksaan: storage/app/browser-check/exploration-map/report.json.

Perubahan kode hanya pada resources/views/pages/exploration.blade.php dan CSS yang dibatasi pada kelas atlas. Route, controller, model, database, autentikasi, dan kuis tidak diubah.

PHP CLI masih menampilkan peringatan konfigurasi iconv serta modul openssl/intl/sodium ganda yang sudah ada. Tes tetap lulus; konfigurasi PHP tidak diubah.
