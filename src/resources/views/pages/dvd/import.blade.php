@extends('layouts.base')

@section('title', 'Import')

@section('content')
    <h2>Import</h2>


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
            <label>CSV</label>
            <small>Format: tmdbid,media_type,disc_type,title,season</small>
            <textarea rows="10" name="csv"></textarea>
            <input type="submit" value="Import"/>
        </form>
    </article>

@endsection
