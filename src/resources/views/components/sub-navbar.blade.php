<ul class="uk-nav uk-nav-default" uk-nav style="padding-top: 1rem;">
    <li class="{{ request()->is('/') ? 'uk-active' : '' }}">
        <a href="/">Home</a>
    </li>

    <li class="{{ request()->is('library*') ? 'uk-active' : '' }}">
        <a href="/library">Library</a>
    </li>

    <li class="{{ request()->is('collections*') ? 'uk-active' : '' }}">
        <a href="/collections">Collections</a>
    </li>

    <li class="{{ request()->is('shows*') ? 'uk-active' : '' }}">
        <a href="/shows">TV Shows</a>
    </li>

    <li class="uk-parent {{ request()->is('settings*') ? 'uk-open uk-active' : '' }}">
        <a href="#">
            Settings
            <span uk-nav-parent-icon></span>
        </a>

        <ul class="uk-nav-sub">
            <li class="{{ request()->is('settings') ? 'uk-active' : '' }}">
                <a href="/settings">Profile</a>
            </li>
            @if(Auth::user()->is_admin)
                <li class="{{ request()->is('jobs*') ? 'uk-active' : '' }}">
                    <a href="/jobs">Meta Jobs</a>
                </li>

                <li class="{{ request()->is('import*') ? 'uk-active' : '' }}">
                    <a href="/import">Import</a>
                </li>
                <li class="{{ request()->is('projects/create') ? 'uk-active' : '' }}">
                    <a href="/settings/users">User Management</a>
                </li>
            @endif
        </ul>
    </li>

    <li>
        <a href="/logout">Logout</a>
    </li>

</ul>