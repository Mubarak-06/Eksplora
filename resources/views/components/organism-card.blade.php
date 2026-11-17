@props(['organism'])
<article class="card organism-card">
    <a href="{{ route('organism',$organism) }}" tabindex="-1" aria-hidden="true"><img src="{{ asset($organism->image) }}" alt="" loading="lazy"></a>
    <div class="card-body"><span class="eyebrow">{{ ucfirst($organism->category) }}</span><h3>{{ $organism->name }}</h3><p><i>{{ $organism->scientific_name }}</i></p><a class="button small" href="{{ route('organism',$organism) }}">Kenali lebih dekat <span aria-hidden="true">→</span></a></div>
</article>
