@extends('layouts.base')

@section('title', $dvd->title)

@section('content')
    <h2>Edit - {{$dvd->title}}</h2>


    <article>

        @if ($errors->any())
            <div class="uk-alert uk-alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="" method="POST" class="form">
            @csrf

            <label>TMDB ID</label>
            <input type="text" name="tmdbid" value="{{$dvd->tmdbid}}"/>
            
            <label>Title</label>
            <input type="text" name="title" value="{{$dvd->title}}"/>
            
            <label>Search Title</label>
            <input type="text" name="search_title" value="{{$dvd->search_title}}"/>
            
            <label>Disc Type</label>
            <select name="disc_type" value="{{$dvd->disc_type}}">
                <option value="dvd">DvD</option>
                <option value="blueray">Blueray</option>
            </select>

            <label>Media Type</label>
            <select name="media_type" value="{{$dvd->media_type}}">
                <option value="tv">TV Show</option>
                <option value="movie">Movie</option>
            </select>

            <label>Release</label>
            <input type="text" name="release" value="{{$dvd->release}}"/>
            
            <label>Season</label>
            <input type="number" name="season" value="{{$dvd->season}}"/>
            
            <label>Season Name</label>
            <input type="text" name="season_name" value="{{$dvd->season_name}}"/>
            
            <label>Series Min</label>
            <input type="number" name="series_min" value="{{$dvd->series_min}}"/>
            
            <label>Series Max</label>
            <input type="number" name="series_max" value="{{$dvd->series_max}}"/>
            
            <label>Collection ID</label>
            <input type="text" name="collection_id" value="{{$dvd->collection_id}}"/>
            
            <label>Collection Name</label>
            <input type="text" name="collection_name" value="{{$dvd->collection_name}}"/>

            <label>Tags</label>
            <input type="hidden" id="tags" name="tags"/>
            <div id="tags-view" class="tags">
                
            </div>

            <input
                type="text"
                id="tag-adder"
                list="tags-list"
                class="uk-input"
                value=""
                autocomplete="off"
                placeholder="Search or add tags"
            >

            <datalist id="tags-list">
                @foreach($tags as $tag)
                    <option value="{{ $tag->name }}">
                @endforeach
            </datalist>
            
            <input type="submit" value="Save"/>
        </form>
    </article>

<script>
(() => {
    let tags = @json($dvd->tags()->pluck("name")->toArray());

    const input = document.getElementById("tag-adder");
    const inputTags = document.getElementById("tags");
    const tagsView = document.getElementById("tags-view");

    const updateView = () => {
        inputTags.value = tags.join(", ");
        let html = "";
        for(let tag of tags) {
            html += `<div class="tag">${tag} <span data-tag="${tag}" class="remove-tag">X</span></div>`;
        }
        tagsView.innerHTML = html;
    };

    tagsView.addEventListener("click", (ev) => {
        const target = ev.target;

        if(target.classList.contains("remove-tag")){
            tags = tags.filter(i => i != target.getAttribute("data-tag"));
            updateView();
        }
    });


    const addTag = (value) => {
        if(tags.indexOf(value) < 0) {
            tags.push(value);
        }
        input.value = "";
        updateView();
    };
    
    input.addEventListener("change", (ev) => {
        const target = ev.target;
        const newValue = target.value;

        addTag(newValue);
    });

    input.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();

            const value = input.value.trim();
            if (!value) return;

            addTag(value);
            input.value = "";
        }
    });

    updateView();

})();
</script>

@endsection
