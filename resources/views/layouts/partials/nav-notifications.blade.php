<div class="dropdown nav-content-notifications">
    <div class="dropdown-link text-center">
        @if($user_groupings->unread_count > 0)
            <span class="icon-notifications_active font-large"></span>
            <span class="badge -error">{{ $user_groupings->unread_count }}</span>
        @else
            <span class="icon-notifications font-large"></span>
        @endif

    </div>
    <div class="dropdown-menu">
        <div class="links -small mtx mbx">
            @forelse($user_groupings->latest_items as $grouping)
                @include("profile.notifications.messages.{$grouping->type->code}-group", [
                    'grouping' => $grouping
                ])
            @empty
                <div class="text-nowrap plm prm pts pbs"> You haven't got any notifications </div>
            @endforelse
        </div>

        <hr>

        <a href="{{ route('profile.notifications.index') }}" class="font-small">
            <div class="ptx pbx plm prm bg-neutral"> All notifications </div>
        </a>
    </div>
</div>