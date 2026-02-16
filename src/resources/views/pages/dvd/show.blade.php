@extends('layouts.base')

@section('title', $dvd->title)

@section('content')
    <h1>{{ $dvd->title }}</h1>

    <article>
        <p>{{$dvd->overview}}</p>
    </article>

    @if($seasons)
        <article>
            <h3>Seasons</h3>
            <div class="seasons">
                @for($i = $dvd->series_min ; $i <= $dvd->series_max ; $i++)
                    @if($seasons->firstWhere('season', $i) )
                        <a href="/dvd/{{ $seasons->firstWhere('season', $i)->id }}" class="season">{{$i}}</a>
                    @else
                        <a href="/check/{{$dvd->media_type}}/{{ $dvd->tmdbid }}?season={{$i}}" class="season missing">{{$i}}</a>
                    @endif
                @endfor
            </div>
        </article>
    @endif


    @if($dvd->collection_id)
        <article>
            <h3>Movie Collection</h3>
            <a href="/collection/{{$dvd->collection_id }}">{{$dvd->collection_title}}</a>
        </article>
    @endif

    <article>
        <img src="https://image.tmdb.org/t/p/w1280{{ $dvd->poster_path }}" />
    </article>

@endsection
