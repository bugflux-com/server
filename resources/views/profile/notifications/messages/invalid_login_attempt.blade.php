<div>
    Attempted from IP <b>{{ $notification->json->ip }}</b>

    <div class="font-small font-neutral" title="{{ $notification->created_at }}">
        {{ $notification->created_at->diffForHumans() }}
    </div>
</div>
