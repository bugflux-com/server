<div class="menu">
    <div class="relative">
        <div class="menu-content">
            <div class="menu-primary @if(!\View::hasSection('menu')) -expanded @endif">
                @include('layouts.partials.menu-primary')
            </div>

            @if(\View::hasSection('menu'))
                <div class="menu-second pbs">
                    @yield('menu')
                </div>
            @endif
        </div>
        <div class="menu-footer">
            @include('layouts.partials.menu-footer')
        </div>
    </div>
</div>