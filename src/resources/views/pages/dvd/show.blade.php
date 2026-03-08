@extends('layouts.base')

@section('title', $dvd->title)

@section('content')

    <h2>{{$dvd->title}}</h2>


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
                        <div class="tag">{{$tag->name}}</div>
                    @endforeach
                </div>
            </td></tr>
            @if($dvd->collection_id)
                <tr><td><strong>Collection</strong></td><td><a href="/collection/{{$dvd->collection_id}}">{{$dvd->collection_name}}</a></td></tr>
            @endif
            @if($dvd->media_type == "tv")
                <tr><td><strong>Season</strong></td><td>{{$dvd->season}} - {{$dvd->season_name}}</td></tr>
            @endif
            <tr><td colspan="2">{{$dvd->overview}}</td></tr>
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
