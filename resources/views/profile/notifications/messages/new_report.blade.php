<div>
    New report for <a href="{{ route('projects.errors.show', [$notification->json->project_id, $notification->json->error_id]) }}"><b>error</b></a>
    in project <a href="{{ route('projects.show', $notification->json->project_id) }}"><b>{{ $notification->json->project }}</b></a>:<br>
    <a href="{{ route('projects.errors.reports.show', [$notification->json->project_id, $notification->json->error_id, $notification->json->report_id]) }}">
        <b>{{ str_limit($notification->json->report) }}</b>
    </a>.

    <div class="font-small font-neutral" title="{{ $notification->created_at }}">
        {{ $notification->created_at->diffForHumans() }}
    </div>
</div>