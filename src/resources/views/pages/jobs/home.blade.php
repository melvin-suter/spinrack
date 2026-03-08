@extends('layouts.base')

@section('title', 'Jobs')

@section('content')
    <h2>Jobs</h2>

    <article>
        <table class="uk-table table">
            <thead>
                <tr>
                    <td>Dvd</td>
                    <td>Type</td>
                    <td>Status</td>
                    <td>Error</td>
                </tr>
            </thead>
            <tbody>
                @foreach($jobs as $job)
                    <tr>
                        <td class="wrap"><a href="/dvd/{{ $job->reference_id }}">{{ (App\Models\Dvd::find($job->reference_id) ?? ['title' => ''])['title'] }}</a></td>
                        <td>{{ $job->type }}</td>
                        <td>
                            @if($job->status == "completed")
                                <span class="uk-label uk-label-success">OK</span>
                            @elseif($job->status == "failed")
                                <span class="uk-label uk-label-danger">ERROR</span>
                            @elseif($job->status == "running")
                                <span class="uk-label uk-label-info">RUN</span>
                            @else
                                <span class="uk-label uk-label-warning">PENDING</span>
                            @endif
                        </td>
                        <td class="wrap">{{ $job->error }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </article>


@endsection
