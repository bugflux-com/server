<div class="dropdown">
    <div class="dropdown-link">
        <img class="ellipsis text-middle" src="{{ route('users.photo', [Auth::user()->id, 'small']) }}" width="24" height="24">
        <span class="mlx"><span class="xs-hide">{{ Auth::user()->name ?: Auth::user()->email }}</span><span class="icon-arrow_drop_down"></span></span>
    </div>
    <div class="dropdown-menu">
        <div class="links -small mtx mbx">
            <a href="{{ route('profile.edit') }}">
                <span class="icon-person"></span>
                <span class="plx">@lang('links.profile')</span>
            </a>
            <a href="{{ url('logout') }}">
                <span class="icon-exit_to_app"></span>
                <span class="plx">@lang('links.logout')</span>
            </a>
        </div>
    </div>
</div>