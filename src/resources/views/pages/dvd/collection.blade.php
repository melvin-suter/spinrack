@extends('layouts.base')

@section('title', 'Collection')

@section('content')
    <h1 id="title">Collection</h1>

    <article>
        <div class="dvd-list" id="list">
        </div>
    </article>

    <script>
(() => {
    const apiKey = "{{ config('app.tmdb_api_key') }}";
    const dvds = @json($dvds);
            
    fetch(`https://api.themoviedb.org/3/collection/{{$collection_id}}?api_key=${apiKey}`).then(async (response) => {
        const data = await response.json();

        document.getElementById('title').innerHTML = data.name;

        for(let part of data.parts) {
            let icon = "";
            let dvd = dvds.filter(i => i.tmdbid == part.id).length == 0 ? null : dvds.filter(i => i.tmdbid == part.id)[0];

            if(dvd == null) {
                icon = `
<div class="missing-icon">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
    </svg>
</div>
                `;
            } else {
                if(dvd.disc_type == "dvd") {
                    icon = `
<div class="dvd-icon">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-disc" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
        <path d="M10 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0M8 4a4 4 0 0 0-4 4 .5.5 0 0 1-1 0 5 5 0 0 1 5-5 .5.5 0 0 1 0 1m4.5 3.5a.5.5 0 0 1 .5.5 5 5 0 0 1-5 5 .5.5 0 0 1 0-1 4 4 0 0 0 4-4 .5.5 0 0 1 .5-.5"/>
    </svg>
</div>
                    `;
                } else {
                    icon = `
<div class="blueray-icon">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-disc" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
        <path d="M10 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0M8 4a4 4 0 0 0-4 4 .5.5 0 0 1-1 0 5 5 0 0 1 5-5 .5.5 0 0 1 0 1m4.5 3.5a.5.5 0 0 1 .5.5 5 5 0 0 1-5 5 .5.5 0 0 1 0-1 4 4 0 0 0 4-4 .5.5 0 0 1 .5-.5"/>
    </svg>
</div>
                    `;
                }

            }

            let newHtml = `
<a ${dvd ? `href="/dvd/${dvd.id}"`: ""} class="dvd">
    <img class="${dvd == null ? "missing" : ""}" src="https://image.tmdb.org/t/p/w154${part.poster_path}"/>
    <div class="front">
        <strong>${part.title}</strong>
        ${icon}
    </div>
</a>
            `;
            document.getElementById('list').innerHTML += newHtml;
        }
        console.log(data);
    });
})();
    </script>
@endsection
