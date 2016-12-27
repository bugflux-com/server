<div>
    {{-- TODO: Tutaj może jeszcze zapisywać informację kto nadał te uprawnienia? Czy to wyświetlać? --}}
    Your role in project <a href="{{ route('projects.show', $notification->json->project_id) }}"><b>{{ $notification->json->project }}</b></a> has changed.
    Now you are a <a href="{{ route('projects.permissions.index', $notification->json->project_id) }}"><b>{{ $notification->json->group }}</b></a>.

    <div class="font-small font-neutral" title="{{ $notification->created_at }}">
        {{ $notification->created_at->diffForHumans() }}
    </div>
</div>