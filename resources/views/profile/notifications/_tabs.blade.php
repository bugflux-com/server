<div class="tabs">
    <a class="tabs-item {{ $master_view == 'profile.notifications.index' ? '-active' : '' }}"
       href="{{ route('profile.notifications.index') }}">
        <span class="icon-list"></span>
        <span> List </span>
    </a>
    <a class="tabs-item {{ $master_view == 'profile.notifications.edit' ? '-active' : '' }}"
       href="{{ route('profile.notifications.edit') }}">
        <span class="icon-settings"></span>
        <span> Settings </span>
    </a>
</div>