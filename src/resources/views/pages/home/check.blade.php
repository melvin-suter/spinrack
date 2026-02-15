@extends('layouts.base')

@section('title', 'Check')

@section('content')
    <h1>Check</h1>
    @if($results->count() > 0)
        <article>
            <h3>Movie already in your collection:</h3>
            <div class="dvd-list">
                @foreach($results as $dvd)
                    @include('components.dvd-view', ['dvd' => $dvd])
                @endforeach
            </div>
        </article>
    @endif

    <article>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
       <h3>Add new</h3>
        <form action="/add-dvd" method="POST">
            @csrf
            <input type="submit" value="Add to library"/>
            <input type="hidden" id="tmdbid" name="tmdbid" value="{{ $id }}"/>
            <input type="hidden" id="poster_path" name="poster_path"/>
            <input type="hidden" id="backdrop_path" name="backdrop_path"/>
            <input type="hidden" id="overview" name="overview"/>
            <input type="hidden" id="disc_type" name="disc_type" value="dvd"/>
            <input type="hidden" id="media_type" name="media_type" value="{{$mediaType}}"/>
            <input type="hidden" id="series_min" name="series_min"/>
            <input type="hidden" id="series_max" name="series_max"/>
            <input type="hidden" id="collection_id" name="collection_id"/>
            <input type="hidden" id="collection_title" name="collection_title"/>

            @if($mediaType == "tv")
                <label><strong>Season</strong></label>
                <input type="number" id="season" name="season" value="1"/>
            @endif

            <label><strong>Title & Year</strong></label>
            <input type="text" id="title" name="title" readonly disable/>
            <input type="text" id="release" name="release" readonly disable/>

            <div role="group">
                <button type="button" id="disc_type_dvd">DvD</button>
                <button type="button" id="disc_type_blueray" class="secondary">Blueray</button>
            </div>

            <img src="" id="poster"/>
        </form>
    </article>
    

        <script>
(() => {
    const disc_type = document.getElementById('disc_type');

    const updateButtonGroup = () => {
        if(disc_type.value == "dvd") {
            document.getElementById('disc_type_dvd').classList.remove("secondary");
            document.getElementById('disc_type_blueray').classList.add("secondary");
        } else {
            document.getElementById('disc_type_blueray').classList.remove("secondary");
            document.getElementById('disc_type_dvd').classList.add("secondary");
        }
    };
    document.getElementById('disc_type_dvd').addEventListener('click',() => {disc_type.value = "dvd"; updateButtonGroup();});
    document.getElementById('disc_type_blueray').addEventListener('click',() => {disc_type.value = "blueray"; updateButtonGroup();});

    const apiKey = "{{ config('app.tmdb_api_key') }}";

    fetch(`https://api.themoviedb.org/3/{{ $mediaType }}/{{ $id }}?api_key=${apiKey}`).then(async (response) => {
        const data = await response.json();
        console.log(data);

        document.getElementById("title").value = data.name || data.title;
        document.getElementById("poster_path").value = data.poster_path;
        document.getElementById("poster").src = "https://image.tmdb.org/t/p/w1280" + data.poster_path;
        document.getElementById("backdrop_path").value = data.backdrop_path;
        document.getElementById("overview").value = data.overview;
        document.getElementById("release").value = data.first_air_date || data.release_date;

        if(data.belongs_to_collection) {
            document.getElementById("collection_id").value = data.belongs_to_collection.id;
            document.getElementById("collection_title").value = data.belongs_to_collection.name;
        }
        if(data.seasons) {
            const sortedSeason = data.seasons.sort((a, b) => a.season_number - b.season_number);
            document.getElementById("series_min").value = sortedSeason[0].season_number;
            document.getElementById("series_max").value = sortedSeason[sortedSeason.length - 1].season_number;
        }

    });
})();
        </script>
@endsection
