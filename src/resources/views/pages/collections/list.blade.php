@extends('layouts.base')

@section('title', 'Collections')

@section('content')
    <h1>Collections</h1>

    <article>
        <div class="dvd-list">
            @foreach($collections as $coll)
                @include('components.dvd-empty-view', [
                    'link' => '/collection/'.$coll['collection_id'],
                    'title' => $coll['collection_title'],
                    'poster_path' => $coll['poster_path'],
                ])
            @endforeach
        </div>
    </article>


@endsection
