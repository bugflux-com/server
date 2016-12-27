<div>
    <table class="table -no-border">
        <colgroup>
            <col width="1">
            <col>
        </colgroup>
        <tr data-href="{{ route('projects.errors.comments.index', [$notification->json->project_id, $notification->json->error_id]) }}">
            <td class="text-top">
                <img class="ellipsis text-middle" src="{{ route('users.photo', [$notification->json->user_id, 'small']) }}" width="24" height="24">
            </td>
            <td>
                <p>{!! nl2br(e($notification->json->message)) !!}</p>
                <p class="font-neutral font-small mtx">
                    <b>{{ $notification->json->user }}</b>,
                    <span title="{{ $notification->created_at }}">{{ $notification->created_at->diffForHumans() }}</span>
                </p>
            </td>
        </tr>
    </table>
</div>