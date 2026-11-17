@props(['activity','subject','label'=>'Tandai Selesai','first'=>null,'second'=>null])
@auth
    @if(auth()->user()->progress()->where('activity',$activity)->where('subject',$subject)->exists())
    <p class="completed">✓ Progres pembelajaran ini sudah tersimpan</p>
    @else
    <form action="{{ route('progress') }}" method="post" class="completion-form">@csrf
        <input type="hidden" name="activity" value="{{ $activity }}"><input type="hidden" name="subject" value="{{ $subject }}">
        @if($first)<input type="hidden" name="first" value="{{ $first }}"><input type="hidden" name="second" value="{{ $second }}">@endif
        <button class="button" type="submit">✓ {{ $label }}</button>
    </form>
    @endif
@else
    <p class="guest-note"><a href="{{ route('login') }}">Masuk</a> untuk menyimpan progres dan mendapatkan poin.</p>
@endauth
