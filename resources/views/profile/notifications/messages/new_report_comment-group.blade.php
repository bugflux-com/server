<a class="iblock" href="{{ route('profile.notifications.show', $grouping->id) }}">
    <div class="panel">
        <div class="panel-shrink -top">
            <span class="icon-info font-info font-huge"></span>
        </div>
        <div class="panel-grow">
            <div class="mls mrs">
                <p @if($grouping->viewed_at == null) class="font-bold" @endif>
                    @if($grouping->notifications()->count() == 1)
                        New comment for one report.
                    @else
                        New {{ $grouping->notifications()->count() }} comments for
                        @if( ($reports_count = $grouping->notifications()->distinct('notificable_id')->count('notificable_id')) == 1 )
                            one report.
                        @else
                            {{ $reports_count }} reports.
                        @endif
                    @endif
                </p>
                <time class="font-small font-neutral" title="{{ $grouping->created_at }}">
                    {{ $grouping->created_at->diffForHumans() }}
                </time>
            </div>
        </div>
    </div>
</a>