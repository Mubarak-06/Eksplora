# Peta eksplorasi ekosistem

Aset: public/images/exploration-wetland-map.webp (1672 x 941 piksel).
Dibuat 17 November 2026 dengan built-in image_gen, menggunakan gambar lampiran pengguna sebagai referensi visual. Gambar hanya ilustrasi, tanpa teks/pin/UI. Berbeda dari hero Beranda. PNG asli tetap tersimpan pada generated_images Codex.

Koordinat pin dihitung terhadap seluruh ilustrasi, berdasarkan slug, bukan urutan database. Gambar ditampilkan utuh dengan aspect-ratio asli dan object-fit: contain. Pusat titik penanda, bukan bubble teksnya, menjadi titik koordinat.

| Habitat | X | Y | Ciri visual yang diperiksa |
| --- | --- | --- | --- |
| Rawa Gambut | 23% | 36% | Air cokelat gelap di dalam hutan rapat, kiri atas |
| Hutan Mangrove | 77% | 30% | Akar tunjang terbuka dan lumpur muara, kanan atas |
| Rawa Pasang Surut | 48% | 55% | Jaringan alur dangkal di antara pulau nipah/rumput, tengah |
| Rawa Lebak | 46% | 82% | Genangan tenang, rumput air dan teratai, bawah tengah |
| Sungai | 85% | 69% | Aliran biru utama yang lebar dan berkelok, kanan |

Pada lebar <= 600px, bubble nama pada ilustrasi digantikan penanda bernomor dengan area klik 44px; nama, nomor, dan ciri habitat tetap terbaca melalui daftar tautan di bawah peta. Semua penanda dan tautan menggunakan route detail yang sama. Navbar dan panel Tentang Lahan Basah dipertahankan.

## Prompt final

Use case: stylized-concept. Create a dedicated educational ecosystem exploration map illustration for EKSPLORA, using the user's attached landscape image as the visual style and composition reference. This must be an environment-only digital illustration, no text, no labels, no map pins, no UI. Wide 16:9 landscape, elevated oblique bird's-eye viewpoint that clearly reads as one connected illustrative habitat map rather than a hero image. Rich detailed modern 2.5D tropical South Kalimantan nature painting, soft bright daylight, green blue palette, atmospheric depth, organically shaped distinct habitat zones. CRITICAL spatial composition with five visibly DIFFERENT habitat regions: (1) upper left x10-35% y20-48% dense peat swamp forest with unmistakable DARK TEA BROWN BLACKWATER, leaf litter and humid dense canopy; (2) upper right x65-89% y15-43% coastal mangrove island with numerous CLEAR EXPOSED STILT ROOTS, gray brown tidal mudflats and pale estuary water beyond; (3) center x36-61% y40-66% tidal marsh maze of interconnected narrow winding shallow channels, exposed muddy banks, nipa palms and sedges on low islands, distinct from the broad river; (4) lower middle x30-65% y68-95% broad quiet inland freshwater marsh with dense aquatic grasses, lily pads and pink water lilies, open low vegetation; (5) right side x75-95% y45-94% a BROAD CONTINUOUS OPEN BLUE RIVER curving vertically from the upper right estuary to the lower right foreground, with clearly defined banks. The landscape must visually distinguish blackwater peat swamp, mangrove stilt roots, a branching tidal channel mosaic, calm lily-rich inland marsh, and open broad river WITHOUT needing labels. Position best recognizable vegetation and water details adjacent to rather than exactly at marker anchor coordinates (peat 23%,36%; mangrove 77%,30%; tidal 48%,55%; inland marsh 46%,82%; river 85%,69%) because HTML pins will be overlaid there later. Keep these five points in their corresponding zones. Use the reference's lush nature detail, but prioritize clear educational habitat geography; avoid a large building, no dominant hornbill, no large foreground animal obscuring habitat, no decorative frames, no simplistic geometric or flat SVG appearance. Entire artwork edge to edge.
