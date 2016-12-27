<div>
    You have got new permission in project <a href="{{ route('projects.errors.index', $notification->json->project_id) }}"><b>{{ $notification->json->project }}</b></a>.
    Your role is <a href="{{ route('projects.permissions.index', $notification->json->project_id) }}"><b>{{ $notification->json->group }}</b></a>.

    <div class="font-small font-neutral" title="{{ $notification->created_at }}">
        {{ $notification->created_at->diffForHumans() }}
    </div>
</div>