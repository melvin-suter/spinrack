<ul class="uk-nav uk-nav-default" uk-nav>
    <li class="{{ request()->is('/') ? 'uk-active' : '' }}">
        <a href="/">Home</a>
    </li>

    <li class="{{ request()->is('library*') ? 'uk-active' : '' }}">
        <a href="/library">Library</a>
    </li>

    <li class="{{ request()->is('jobs*') ? 'uk-active' : '' }}">
        <a href="/jobs">Meta Jobs</a>
    </li>

    <li class="{{ request()->is('import*') ? 'uk-active' : '' }}">
        <a href="/import">Import</a>
    </li>

    <!--

    <li class="uk-parent {{ request()->is('projects*') ? 'uk-open uk-active' : '' }}">
        <a href="#">
            Projects
            <span uk-nav-parent-icon></span>
        </a>

        <ul class="uk-nav-sub">
            <li class="{{ request()->is('projects') ? 'uk-active' : '' }}">
                <a href="/projects">All Projects</a>
            </li>

            <li class="{{ request()->is('projects/create') ? 'uk-active' : '' }}">
                <a href="/projects/create">New Project</a>
            </li>
        </ul>
    </li>-->

    <li class="{{ request()->is('settings*') ? 'uk-active' : '' }}">
        <a href="/settings">Settings</a>
    </li>

    <li>
        <a href="/logout">Logout</a>
    </li>

</ul>