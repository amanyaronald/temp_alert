<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() === 'dashboard' ? '' : 'collapsed' }}"
                href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <!-- End Dashboard Nav -->

        <li class="nav-heading">Pages</li>
        @foreach ($menus as $menu)
            @can(str_replace('-', '', $menu->route))
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() === $menu->route ? '' : 'collapsed' }}"
                        href="{{ route($menu->route) }}">
                        <i class="{{ $menu->icon }}"></i>
                        <span>{{ $menu->name }}</span>
                    </a>
                </li>
            @endcan
        @endforeach


</aside>
