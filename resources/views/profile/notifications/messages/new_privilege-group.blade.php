<a class="iblock" href="{{ route('profile.notifications.show', $grouping->id) }}">
    <div class="panel">
        <div class="panel-shrink -top">
            <span class="icon-info font-info font-huge"></span>
        </div>
        <div class="panel-grow">
            <div class="mls mrs">
                <p @if($grouping->viewed_at == null) class="font-bold" @endif>
                    @if( ($count = $grouping->notifications()->count()) == 1)
                        You have got one new permission.
                    @else
                        You have got {{ $count }} new permissions.
                    @endif
                </p>
                <time class="font-small font-neutral" title="{{ $grouping->created_at }}">
                    {{ $grouping->created_at->diffForHumans() }}
                </time>
            </div>
        </div>
    </div>
</a>