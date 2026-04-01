<a href="{{$link}}" class="dvd">
    @if($poster_path)
        <img src="https://image.tmdb.org/t/p/w154{{ $poster_path }}"/>
    @else
        <img src="/placeholder.png"/>
    @endif

    <div class="front">
        <strong style="font-size: 0.9rem">{{$title}}</strong>
        <div></div>
    </div>
</a>