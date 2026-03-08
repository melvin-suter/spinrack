@extends('layouts.base')

@section('title', 'Library')

@section('content')
    <h2>Library</h2>

    <article>
        <form action="" method="GET">
            <input type="search" placeholder="Search" name="s" value="{{ $search }}"/>
            <input type="submit" value="Search"/>
    </form>
    </article>


    <article>
        <div class="dvd-list">
            @foreach($dvds as $dvd)
                @include('components.dvd-view', ['dvd' => $dvd])
            @endforeach
        </div>
    </article>


@endsection
