<a class="iblock" href="{{ route('profile.notifications.show', $grouping->id) }}">
    <div class="panel">
        <div class="panel-shrink -top">
            <span class="icon-warning font-warning font-huge"></span>
        </div>
        <div class="panel-grow">
            <div class="mls mrs">
                <p @if($grouping->viewed_at == null) class="font-bold" @endif>
                    @if($grouping->notifications()->count() == 1)
                        New error in one project.
                    @else
                        New {{ $grouping->notifications()->count() }} errors in
                        @if( ($projects_count = $grouping->notifications()->distinct('notificable_id')->count('notificable_id')) == 1 )
                            one project.
                        @else
                            {{ $projects_count  }} projects.
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