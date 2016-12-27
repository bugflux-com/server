<div>
    New error in project <a href="{{ route('projects.show', $notification->json->project_id) }}"><b>{{ $notification->json->project }}</b></a>:<br>
    <a href="{{ route('projects.errors.show', [$notification->json->project_id, $notification->json->error_id]) }}"><b>{{ str_limit($notification->json->error) }}</b></a>.

    <div class="font-small font-neutral" title="{{ $notification->created_at }}">
        {{ $notification->created_at->diffForHumans() }}
    </div>
</div>