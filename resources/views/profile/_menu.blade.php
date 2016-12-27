<div class="container fluid">
    <div class="panel mts">
        <div class="panel-shrink -top">
            <img class="mtx ellipsis text-middle" src="{{ route('users.photo', [Auth::user()->id, 'small']) }}" width="40" height="40">
        </div>
        <div class="panel-grow text-overflow pls">
            <h1> Your profile </h1>
            <span class="font-neutral text-overflow">{{ Auth::user()->name ?: Auth::user()->email }}</span>
        </div>
    </div>
</div>

<div class="links mts">
    <a href="{{ route('profile.edit') }}" @if(in_array($master_view, ['profile.edit', 'profile.new_email'])) class="current" @endif>
        <span class="icon-person"></span>
        <span class="plx"> Basic informations </span>
    </a>
    <a href="{{ route('profile.notifications.index') }}" @if(starts_with($master_view, 'profile.notifications.')) class="current" @endif>
        <span class="icon-notifications"></span>
        <span class="plx"> Notifications </span>
    </a>
    {{-- TODO: Dodać podstronę na której możemy zobaczyć nasze uprawnienia --}}
    <a href="{{ route('profile.permissions.index') }}" @if($master_view == 'profile.permissions.index') class="current" @endif>
        <span class="icon-lock"></span>
        <span class="plx"> Permissions </span>
    </a>
</div>