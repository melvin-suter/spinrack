@extends('layouts.base')

@section('title', 'TV Shows')

@section('content')
    <h1>TV Shows</h1>

    <article>
        <div class="dvd-list">
            @foreach($shows as $show)
                @include('components.dvd-empty-view', [
                    'link' => '/dvd/'.$show['dvd_id'],
                    'title' => $show['title'],
                    'poster_path' => $show['poster_path'],
                ])
            @endforeach
        </div>
    </article>


@endsection
