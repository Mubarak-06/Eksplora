@extends('layouts.app')
@section('title','Tentang Lahan Basah')
@section('content')
<section class="banner"><div><span class="eyebrow">MENGENAL RUMAH KEHIDUPAN</span><h1>Tentang Lahan Basah</h1><p>Air, tanah, tumbuhan, dan hewan saling terhubung. Mari kenali peran lahan basah di sekitar kita.</p></div><img src="{{ asset('images/rawa-lebak.webp') }}" alt="Ilustrasi rawa"></section>
<div class="grid two">
@foreach([
    ['💧','Pengertian Lahan Basah','Lahan basah merupakan wilayah dengan air yang menggenangi atau menjenuhi tanah, secara tetap maupun musiman. Ada yang berair tawar, payau, atau asin. Rawa, sungai, dan mangrove adalah contoh habitat lahan basah.'],
    ['🌧','Proses Terbentuknya Lahan Basah','Air hujan, luapan sungai, pasang laut, atau air tanah dapat terkumpul di suatu tempat. Jika aliran keluar lambat, tanah menjadi jenuh air. Gambut terbentuk ketika sisa tumbuhan menumpuk dan terurai sangat lambat dalam kondisi basah.'],
    ['🌿','Karakteristik Lahan Basah','Air menjadi bagian penting habitat. Tanahnya sering jenuh air dan memiliki sedikit oksigen. Jenis tumbuhan, kedalaman air, serta kadar garam berbeda menurut ekosistem dan musim.'],
    ['🤝','Manfaat Lahan Basah','Lahan basah menjadi rumah organisme, menampung air, menyimpan karbon, dan mendukung kehidupan masyarakat. Vegetasi membantu menahan sedimen. Manfaat ini bergantung pada keadaan ekosistem yang sehat.'],
    ['⚠','Ancaman terhadap Lahan Basah','Pencemaran, timbunan sampah, penimbunan rawa, pembukaan vegetasi, dan pengeringan gambut dapat merusak habitat. Kerusakan di satu tempat dapat memengaruhi wilayah lain yang terhubung oleh air.'],
    ['🌱','Pelestarian Lahan Basah','Kurangi sampah, hindari membakar lahan, dan rawat vegetasi alami. Ajak teman mengamati alam tanpa menangkap satwa. Kegiatan di dekat air harus didampingi orang dewasa dan dilakukan dari tempat aman.']
] as [$icon,$title,$text])<article class="card info-card"><span class="feature-icon" aria-hidden="true">{{ $icon }}</span><div><h2>{{ $title }}</h2><p>{{ $text }}</p></div></article>@endforeach
</div>
<div class="action-row"><a class="button secondary" href="{{ route('exploration') }}">← Kembali ke Eksplorasi</a><x-complete activity="basic" subject="tentang-lahan-basah" label="Saya Sudah Mempelajari Materi Dasar" /></div>
@endsection
