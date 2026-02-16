@extends('layouts.base')

@section('title', 'Tag')

@section('content')
    <h2>Tag {{$tag->name}}</h2>

    <article>
        <div class="dvd-list">
            @foreach($dvds as $dvd)
                @include('components.dvd-view', ['dvd' => $dvd])
            @endforeach
        </div>
    </article>

    
@endsection
