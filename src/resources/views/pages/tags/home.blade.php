@extends('layouts.base')

@section('title', 'Tag - '.$tag->name)

@section('content')
    <h1>Tag - {{$tag->name}}</h1>

    <article>
        <div class="dvd-list">
            @foreach($dvds as $dvd)
                @include('components.dvd-view', ['dvd' => $dvd])
            @endforeach
        </div>
    </article>


@endsection
