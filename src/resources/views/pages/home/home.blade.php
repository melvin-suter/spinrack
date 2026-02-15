@extends('layouts.base')

@section('title', 'Home')

@section('content')
    <h2>Home</h2>

    <article>
        <h3>Check DvD</h3>
        <input type="search" placeholder="Title" id="search"/>
        <article style="margin-bottom: 0px; padding: 0px;">
            <div id="searchResult">
            </div>
        </article>
    </article>

    <article>
        <h3>In your library</h3>

        Total: {{ $discCount }}
        DvDs: {{ $dvdCount }}
        Blueray: {{ $bluerayCount }}
    </article>


    <article>
        <h3>Newest DvDs</h3>

        <div class="dvd-list">
            @foreach($dvds as $dvd)
                @include('components.dvd-view', ['dvd' => $dvd])
            @endforeach
        </div>
    </article>


    <script>
        (() => {
            const searchInput = document.getElementById('search');
            const searchResult = document.getElementById('searchResult');
            let searchTimeout = null;


            searchResult.addEventListener('click', (event) => {
                const td = event.target.closest('td');

                // If click wasn't inside a TD, ignore it
                if (!td || !searchResult.contains(td)) return;

                console.log('Adding Movie:', td.getAttribute("data-id"));
            });

            searchInput.addEventListener('keyup', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const apiKey = "{{ config('app.tmdb_api_key') }}";
                    const languages = "{{ config('app.tmdb_languages') }}".split(",");
                
                    searchResult.innerHTML = "";

                    for(let lang of languages) {

                        fetch(`https://api.themoviedb.org/3/search/multi?api_key=${apiKey}&query=${searchInput.value}&language=${lang}&region=US&sort_by=popularity.desc`).then(async (response) => {
                            const data = await response.json();
                            console.log(data);
                            for(let item of data.results.slice(0, 10)) {
                                let newHtml = `
                                <a href="/check/${item.media_type}/${item.id}" class="movie-selector" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://image.tmdb.org/t/p/w1280${item.backdrop_path}');">                                    
                                    <img src="https://image.tmdb.org/t/p/w154${item.poster_path}"/>
                                    <div>
                                        <strong>${item.name || item.title} <small>(${item.first_air_date || item.release_date})</small></strong>
                                        <p>${item.overview}</p>
                                    </div>
                                </a>`;

                                searchResult.innerHTML += newHtml;
                            }
                        });
                        
                    }
                }, 500);
            });
        })();
    </script>

@endsection
