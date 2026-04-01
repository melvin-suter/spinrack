@extends('layouts.base')

@section('title', 'Genre - '.$genre->name)

@section('content')
    <h1>Genre - {{$genre->name}}</h1>

    <article>
        <div class="dvd-list">
            @foreach($dvds as $dvd)
                @include('components.dvd-view', ['dvd' => $dvd])
            @endforeach
        </div>
    </article>


@endsection
