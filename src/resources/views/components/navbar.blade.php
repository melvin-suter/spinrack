<div class="app-shell">

    {{-- Mobile top bar --}}
    <nav class="uk-navbar-container uk-hidden@m" uk-navbar>
            <button class="uk-button uk-button-default" type="button" uk-toggle="target: #mobile-sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                </svg>
            </button>
    </nav>

    <div class="uk-grid-collapse" uk-grid>
        {{-- Desktop sidebar --}}
        <aside class="app-sidebar uk-visible@m">
            <div class="uk-padding">
                @include('components.sub-navbar')
            </div>
        </aside>

        {{-- Main content --}}
        <main class="app-content uk-width-expand">
            <div class="uk-padding">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Mobile offcanvas sidebar --}}
    <div id="mobile-sidebar" uk-offcanvas="overlay: true">
        <div class="uk-offcanvas-bar">
            <button class="uk-offcanvas-close" type="button" uk-close></button>
            
            @include('components.sub-navbar')
        </div>
    </div>
</div>