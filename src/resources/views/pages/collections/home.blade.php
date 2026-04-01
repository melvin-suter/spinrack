@extends('layouts.base')

@section('title', 'Collection')

@section('content')
    <h1>Collection</h1>

    <article>
        <div class="dvd-list">
            @foreach($parts as $dvd)
                @if($dvds->where('tmdbid',$dvd['id'])->first())
                    @include('components.dvd-view', ['dvd' => $dvds->where('tmdbid',$dvd['id'])->first()])
                @else
                    @include('components.dvd-view', ['new'  => true, 'dvd' => new App\Models\Dvd([
                        'tmdbid' => $dvd['id'],
                        'media_type' => 'movie',
                        'title' => $dvd['title'],
                        'poster_path' => $dvd['poster_path'],
                    ])])
                @endif
            @endforeach
        </div>
    </article>


@endsection
