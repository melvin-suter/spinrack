@extends('layouts.base')

@section('title', 'Check')

@section('content')
    <h1>Check</h1>

        @if ($errors->any())
            <div class="uk-alert uk-alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    <article>

    @if($results->count() > 0)
        @if($mediaType == "movie")
            <div class="uk-alert">
                @foreach($results as $dvd)
                    <span>A {{$dvd->disc_type}} already in collection.</span>
                @endforeach
            </div>
        @else
            <div class="uk-alert seasons">
               @if($results->first())
                    @for($i = $results->first()->series_min ; $i <= $results->first()->series_max ; $i++)
                        @if($results->firstWhere('season', $i) )
                            <a href="/dvd/{{ $results->firstWhere('season', $i)->id }}" class="season">{{$i}}</a>
                        @else
                            <a href="/check/{{$results->first()->media_type}}/{{ $results->first()->tmdbid }}?season={{$i}}" class="season missing">{{$i}}</a>
                        @endif
                    @endfor
                @endif
            </div>
        @endif
    @endif
        
       <h3>Add new</h3>
        <form action="/add-dvd" method="POST" class="form">
            @csrf
            <input type="hidden" name="media_type" value="{{$mediaType}}"/>
            <input type="submit" value="Add to library"/>

            @if($mediaType == "tv")
                <label>Season</label>
                <input type="text" id="season" name="season" value="{{ request('season',1) }}"/>
            @endif

            <label>TMDB ID</label>
            <input type="text" id="tmdbid" name="tmdbid" value="{{ $id }}"/>
            

            <label>Title</label>
            <input type="text" id="title" name="title"/>

            <label>Disc Type</label>
            @include('components.switch', [
                'name' => "disc_type",
                'options' => [
                    'dvd' => "DvD",
                    "blueray" => "Blueray"
                ],
                'defaultValue' => 'dvd'
            ])

            <label>Media Type</label>
            @include('components.switch', [
                'name' => "media_type",
                'options' => [
                    'tv' => "TV Series",
                    "movie" => "Movie"
                ],
                'defaultValue' => $mediaType
            ])

        </form>
    </article>

    <article>
        <div class="poster-view">
            <img src="" id="poster"/>
        </div>
    </article>




        <script>
(() => {

    const seasonField = document.getElementById("season")
    if(seasonField) {
        seasonField.focus();
    }

    
    const apiKey = "{{ config('app.tmdb_api_key') }}";

    fetch(`https://api.themoviedb.org/3/{{ $mediaType }}/{{ $id }}?api_key=${apiKey}`).then(async (response) => {
        const data = await response.json();

        document.getElementById("title").value = data.name || data.title;
        document.getElementById("poster").src = "https://image.tmdb.org/t/p/w1280" + data.poster_path;
        

    });
})();
        </script>
@endsection
