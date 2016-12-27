<div class="container fluid">
    <div class="panel mts">
        <div class="panel-shrink -top">
            <img class="mtx ellipsis text-middle" src="{{ route('users.photo', [$user->id, 'small']) }}" width="40" height="40">
        </div>
        <div class="panel-grow text-overflow pls">
            <h1 class="text-overflow">{{ $user->name or $user->email }}</h1>
            <span class="font-neutral text-overflow"> User profile </span>
        </div>
    </div>
</div>

<div class="links mts">
    <a href="{{ route('users.show', $user->id) }}" @if($master_view == 'users.show') class="current" @endif>
        <span class="icon-person"></span>
        <span class="plx"> Basic informations </span>
    </a>

    <a href="{{ route('users.permissions.index', $user->id) }}" @if(starts_with($master_view, 'users.permissions.')) class="current" @endif>
        <span class="icon-lock"></span>
        <span class="plx"> Permissions </span>
    </a>
</div>