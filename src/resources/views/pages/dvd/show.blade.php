@extends('layouts.base')

@section('title', $dvd->title)

@section('content')

    <h1>{{$dvd->title}}</h1>


    <article>
        <div class="button-group-right">
            <button uk-toggle="target: #deleteModal" type="button" class="danger" style="width: auto; font-size: 1rem;">Delete</button>
            <a href="/dvd/{{$dvd->id}}/requeue" class="button">Reload Meta Data</a>
            <a href="/dvd/{{$dvd->id}}/edit" class="button">Edit</a>
        </div>
        <table>
            <tr><td><strong>Title</strong></td><td>{{$dvd->title}}</td></tr>
            <tr><td><strong>TMDB ID</strong></td><td>{{$dvd->tmdbid}}</td></tr>
            <tr><td><strong>Disc Type</strong></td><td>{{$dvd->disc_type}}</td></tr>
            <tr><td><strong>Media Type</strong></td><td>{{$dvd->media_type}}</td></tr>
            <tr><td><strong>Release</strong></td><td>{{$dvd->release}}</td></tr>
            <tr><td><strong>Tags</strong></td><td>
                <div class="tags">
                    @foreach($dvd->tags()->get() as $tag)
                        <a href="/tag/{{$tag->id}}" class="tag">{{$tag->name}}</a>
                    @endforeach
                </div>
            </td></tr>
            <tr><td><strong>Genres</strong></td><td>
                <div class="genres">
                    @foreach($dvd->genres()->get() as $genre)
                        <a href="/genre/{{$genre->id}}" class="tag">{{$genre->name}}</a>
                    @endforeach
                </div>
            </td></tr>
            @if($dvd->collection_id != null)
                <tr><td><strong>Collection</strong></td><td><a href="/collection/{{$dvd->collection_id}}">{{$dvd->collection_title ? $dvd->collection_title : 'Collection'}}</a></td></tr>
            @endif
            @if($dvd->media_type == "tv")
                <tr><td><strong>Season</strong></td><td>{{$dvd->season}} - {{$dvd->season_name}}</td></tr>
            @endif
            <tr><td colspan="2">{{$dvd->overview}}</td></tr>
            @if($dvd->media_type == "tv")
                <tr><td colspan="2">
                    <strong>Seasons</strong>
                    <div class="seasons">
                        @if($seasons->first())
                            @for($i = $seasons->first()->series_min ; $i <= $seasons->first()->series_max ; $i++)
                                @if($seasons->firstWhere('season', $i) )
                                    <a href="/dvd/{{ $seasons->firstWhere('season', $i)->id }}" class="season">{{$i}}</a>
                                @else
                                    <a href="/check/{{$seasons->first()->media_type}}/{{ $seasons->first()->tmdbid }}?season={{$i}}" class="season missing">{{$i}}</a>
                                @endif
                            @endfor
                        @endif
                    </div>
                </td></tr>
            @endif
        </table>
    </article>


    <article>
        <div class="poster-view">
            @if($dvd->poster_path)
                <img src="https://image.tmdb.org/t/p/w154{{ $dvd->poster_path }}"/>
            @else
                <img src="/placeholder.png"/>
            @endif
        </div>
    </article>


<div id="deleteModal" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Delete the dvd "{{$dvd->title}}"?</h2>

        <div class="button-group-right">
            <a href="/dvd/{{$dvd->id}}/delete" class="button danger">Yes</a>
            <button class="uk-modal-close" type="button">No</button>
        </div>
    </div>
</div>
@endsection
