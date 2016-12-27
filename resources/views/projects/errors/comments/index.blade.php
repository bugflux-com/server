@extends('layouts.app')
@section('title', 'Comments')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.errors.index', $project->id) }}"> Errors </a>
        <a href="{{ route('projects.errors.show', [$project->id, $error->id]) }}">{{ str_limit($error->name, 48) }}</a>
        <a href="{{ route('projects.errors.comments.index', [$project->id, $error->id]) }}"> Comments </a>
    </div>
@endsection

@section('menu')
    @include('projects.errors._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbs">
            <div class="text-right">
                <span class="input-group mtx">
                        <div class="dropdown">
                            <div class="dropdown-link">
                                <span class="icon-notifications"></span><span class="icon-arrow_drop_down"></span>
                            </div>
                            <div class="dropdown-menu">
                                <div class="text-left font-normal mls mrs mts mbs" style="min-width: 200px; max-width: 100%">
                                    {{ Form::open('put', route('profile.notifications.errors.comments.update', $error->id)) }}

                                    <div class="form-field">
                                        {{ Form::label('new_error_comment', 'New comment') }}
                                        <div class="font-small font-neutral mbx"> Inform about new comments for this error (exclude reports comments). </div>

                                        <div>
                                            <span class="iblock mrm">
                                                {{ Form::hidden('new_error_comment.internal', 0) }}
                                                {{ Form::checkbox('new_error_comment.internal', 1, "Internal", $notifications->pivot && $notifications->pivot->internal) }}
                                            </span>
                                            <span class="iblock">
                                                {{ Form::hidden('new_error_comment.email', 0) }}
                                                {{ Form::checkbox('new_error_comment.email', 1, "E-mail", $notifications->pivot && $notifications->pivot->email) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-field">
                                        {{ Form::submit(null, ['small' => 'true']) }}
                                    </div>

                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </span>

                @include('common.pagination.simple', ['paginator' => $comments, 'class' => 'mtx mls xs-hide'])
            </div>

            @include('common.alert')

            <div class="scroll-x">
                <table class="table mts">
                    <colgroup>
                        <col width="1">
                        <col>
                        <col width="1">
                    </colgroup>
                    <thead>
                    <tr>
                        <th></th>
                        <th> Message </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($comments as $comment)
                        <tr>
                            <td class="text-top">
                                <img class="ellipsis text-middle" src="{{ route('users.photo', [$comment->user->id, 'small']) }}" width="24" height="24">
                            </td>
                            <td>
                                <p>{!! nl2br(e($comment->message)) !!}</p>
                                <p class="font-neutral font-small mtx">
                                    @can('read', $comment->user)
                                        <b title="{{ $comment->user->email }}"><a class="font-neutral" href="{{ route('users.show', $comment->user->id) }}">{{ $comment->user->name or $comment->user->email }}</a></b>,
                                    @endcan
                                    @cannot('read', $comment->user)
                                        <b title="{{ $comment->user->email }}">{{ $comment->user->name or $comment->user->email }}</b>,
                                    @endcannot
                                    <span title="{{ $comment->created_at }}">{{ $comment->created_at->diffForHumans() }}</span>
                                </p>
                            </td>
                            <td class="text-nowrap text-top">
                                <div class="table-row-hover">
                                    @can('update', [$comment, $error->project_id])
                                        <a href="{{ route('projects.errors.comments.edit', [$error->project_id, $error->id, $comment->id]) }}"
                                           class="button -small" title="Edit comment"
                                           onclick="event.stopPropagation()">
                                            <span class="icon-mode_edit"></span>
                                        </a>
                                    @endcan
                                    @can('delete', [$comment, $error->project_id])
                                        {{ Form::open('delete', route('projects.errors.comments.destroy', [$error->project_id, $error->id, $comment->id]), false, ['style' => 'display: inline']) }}
                                        {{-- TODO: Zmienić onclick na własny modal --}}
                                        <button class="button -small" type="submit"
                                                title="Delete comment"
                                                onclick="event.stopPropagation(); return confirm('Are you sure you want to delete this comment?')">
                                            <span class="icon-delete"></span>
                                        </button>
                                        {{ Form::close() }}
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if($comments->currentPage() == $comments->lastPage() || $comments->total() == 0)
                        <tr>
                            <td class="text-top">
                                <img class="ellipsis text-middle" src="{{ route('users.photo', [Auth::user()->id, 'small']) }}" width="24" height="24">
                            </td>
                            <td>
                                {{ Form::open('post', route('projects.errors.comments.store', [$error->project_id, $error->id])) }}

                                <div class="form-field">
                                    {{ Form::text('message') }}
                                    {{ Form::error('message') }}

                                    <div class="mtx">
                                        {{ Form::submit('Comment', ['small' => 'true']) }}
                                    </div>
                                </div>

                                {{ Form::close() }}
                            </td>
                            <td></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            @include('common.pagination.full', ['paginator' => $comments, 'class' => 'mts'])
        </div>
    </div>
@endsection