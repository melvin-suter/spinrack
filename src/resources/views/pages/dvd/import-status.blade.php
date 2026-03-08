@extends('layouts.base')

@section('title', 'Import')

@section('content')
    <h2>Import</h2>


    <article>
        <table class="uk-table table">
            <thead>
                <tr>
                    <th>Line</th>
                    <th>TMDB ID</th>
                    <th>Media Type</th>
                    <th>Disc Type</th>
                    <th>Title</th>
                    <th>Season</th>
                    <th>Status</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $row)
                    <tr>
                        <td>{{ $row['line'] }}</td>
                        <td>{{ $row['tmdbid'] }}</td>
                        <td>{{ $row['media_type'] }}</td>
                        <td>{{ $row['disc_type'] }}</td>
                        <td class="wrap">{{ $row['title'] }}</td>
                        <td>{{ $row['season'] }}</td>
                        <td>
                            @if($row['success'])
                                <span class="uk-label uk-label-success">OK</span>
                            @else
                                <span class="uk-label uk-label-danger">Failed</span>
                            @endif
                        </td>
                        <td>{{ $row['message'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </article>

@endsection
