@extends('layouts.base')

@section('title', $dvd->title)

@section('content')
    <h1>{{ $dvd->title }}</h1>

    <article>
        <h3>Edit</h3>
        <form action="" method="POST">
            @csrf

            @if($dvd->media_type == "tv")
                <label>Season</label>
                <input type="number" id="season" name="season" value="{{$dvd->season}}"/>
            @endif

            <input type="hidden" id="disc_type" name="disc_type" value="{{ $dvd->disc_type }}"/>

            <div role="group">
                <button type="button" id="disc_type_dvd">DvD</button>
                <button type="button" id="disc_type_blueray" class="secondary">Blueray</button>
            </div>

            <input type="submit" class="w-100" value="Save"/>
        </form>
    </article>

    @if($seasons)
        <article>
            <h3>Seasons</h3>
            <div class="seasons">
                @for($i = $dvd->series_min ; $i <= $dvd->series_max ; $i++)
                    <div class="season {{in_array($i, $seasons) ? "" : "missing" }}">{{$i}}</div>
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
        <a id="delete" class="red w-100" role="button">Delete</a>
    </article>

    <dialog id="delete-modal">
        <article>
            <header>
            <button id="close" aria-label="Close" rel="prev"></button>
            <p>
                <strong>Delete?</strong>
            </p>
            </header>
            <p>Delete this DvD?</p>
            <a href="/dvd/{{ $dvd->id }}/delete" class="red" role="button">Delete</a>
            <a id="no" class="secondary" role="button">No</a>
        </article>
    </dialog>


    <script>
(() => {

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
    updateButtonGroup();

    document.getElementById("delete").addEventListener("click", () => {
        document.getElementById("delete-modal").setAttribute("open", true);
    });

    document.getElementById("close").addEventListener("click", () => {
        document.getElementById("delete-modal").setAttribute("open", false);
    });
    document.getElementById("no").addEventListener("click", () => {
        document.getElementById("delete-modal").setAttribute("open", false);
    });

})();
    </script>
@endsection
