<div class="nav">
    <div class="nav-menu">
        <span class="nav-menu-icon icon-menu"></span>
        <span class="nav-menu-text"> Menu </span>
    </div>
    <div class="nav-content">
        <div class="container fluid">
            <div class="panel">
                <div class="panel-grow text-overflow">
                    @yield('breadcrumb')
                </div>
                <div class="panel-shrink">
                    @include('layouts.partials.nav-notifications')
                    @include('layouts.partials.nav-profile')
                </div>
            </div>
        </div>
    </div>
</div>