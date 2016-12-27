@extends('layouts.app')
@section('title', 'Error for '. $project->name)

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.errors.index', $project->id) }}"> Errors </a>
    </div>
@endsection

@section('menu')
    @include('projects._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbx">
            <div class="text-right">
                {{ Form::open('get', route('projects.errors.index', [$project->id]), false, ['class' => 'iblock'], false) }}

                <span class="input-group mtx mrs">
                    {{ Form::select('sort_column', $sort_column_names, $sort_col_request, ['class' => '-small', 'submit-on-change' => true] ) }}
                    {{ Form::select('sort_direction', $sort_direction_names, $sort_dir, ['class' => '-small', 'submit-on-change' => true] ) }}
                </span>

                <span class="input-group text-top mtx mrs">
                    {{ Form::select('filter_env', $filter_env_view_names, $env_filter_request, ['class' => '-small', 'submit-on-change' => true] ) }}
                    {{ Form::select('tag', $tags_names_ids, $tag, ['class' => '-small', 'submit-on-change' => true] ) }}
                </span>

                {{ Form::close() }}

                <span class="input-group mtx @if($errors->hasPages()) mrs @endif">
                    <div class="dropdown">
                        <div class="dropdown-link">
                            <span class="icon-notifications"></span><span class="icon-arrow_drop_down"></span>
                        </div>
                        <div class="dropdown-menu">
                            <div class="font-normal mls mrs mts mbs text-left" style="min-width: 200px; max-width: 100%">
                                {{ Form::open('put', route('profile.notifications.errors.update', $project->id)) }}

                                <div class="form-field">
                                    {{ Form::label('new_error', 'New error') }}
                                    <div class="font-small font-neutral mbx"> Inform about new types of errors. </div>

                                    <div>
                                        <span class="iblock mrm">
                                            {{ Form::hidden('new_error.internal', 0) }}
                                            {{ Form::checkbox('new_error.internal', 1, "Internal", $notifications->pivot && $notifications->pivot->internal) }}
                                        </span>
                                        <span class="iblock">
                                            {{ Form::hidden('new_error.email', 0) }}
                                            {{ Form::checkbox('new_error.email', 1, "E-mail", $notifications->pivot && $notifications->pivot->email) }}
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

                @include('common.pagination.simple', ['paginator' => $errors, 'class' => 'mtx xs-hide'])
            </div>

            @include('common.alert')

            @if(count($errors)>0)
                <div class="scroll-x mts">
                    {{-- TODO: Dodać kolumny z trendem dla liczby błędów i raportów --}}
                    <table class="table">
                        <colgroup>
                            <col width="1">
                            <col>
                            <col width="1">
                            <col width="1">
                            <col width="60">
                            <col width="60">
                            <col width="120">
                            <col width="1">
                            {{-- TODO: Liczba komentarzy? --}}
                        </colgroup>
                        <thead>
                        <tr>
                            <th> # </th>
                            <th> Name </th>
                            <th></th>
                            <th class="text-nowrap"> Trend (7D) </th>
                            <th> Total </th>
                            <th> Users </th>
                            <th class="text-nowrap"> Last seen </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($errors as $error)
                            {{-- TODO: Przenieść logikę sprawdzania uprawnien do kontrolera --}}
                            @can('read', $error)
                                <tr data-href="{{ route('projects.errors.reports.index', [$project->id, $error->id]) }}">
                                    <td>{{ $error->id }}</td>
                                    <td>
                                        {{ str_limit($error->name, 150) }}
                                        @can('readAny', [\App\Models\Tag::class, $error->project])
                                            @foreach($error->tags as $tag)
                                                <a href="{{ route('projects.errors.index', [$error->project_id, 'tag' => $tag->id]) }}">
                                                    <span class="label text-top" style="background: {{ $tag->hex }}">
                                                        {{ $tag->name }}
                                                    </span>
                                                </a>
                                            @endforeach
                                        @endcan
                                    </td>
                                    <td class="text-nowrap">
                                        @if($error->comments_count > 0)
                                            <a href="{{ route('projects.errors.comments.index', [$error->project_id, $error->id]) }}">
                                                <span class="icon-question_answer"></span>
                                                <span class="font-small">{{ $error->comments_count }}</span>
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        @include('projects.errors.reports.charts.trend', [
                                            'period' => $trend_for_days,
                                            'reports' => $error->reportsTrend
                                        ])
                                    </td>
                                    <td>{{ $error->reports_count }}</td>
                                    <td>{{ $error->clientsCount }}</td>
                                    <td class="text-nowrap">
                                        <span title="{{ $error->updated_at }}">
                                            {{ $error->updated_at->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="table-row-hover">
                                            {{-- TODO: Ukrywać kolumnę na urządzeniach mobilnych? A może zawsze wyświetlać przyciski? --}}
                                            {{-- TODO: Przenieść onclick="event.stopPropagation()" do logiki tabel w js --}}
                                            @can('update', $error)
                                                <a href="{{ route('projects.errors.edit', [$error->project_id, $error->id]) }}"
                                                   class="button -small" title="Edit error"
                                                   onclick="event.stopPropagation()">
                                                    <span class="icon-mode_edit"></span>
                                                </a>
                                            @endcan
                                            @can('delete', $error)
                                            {{ Form::open('delete', route('projects.errors.destroy', [$project->id, $error->id]), false, ['style' => 'display: inline']) }}
                                            {{-- TODO: Zmienić onclick na własny modal --}}
                                            <button class="button -small" type="submit"
                                                    title="Delete error"
                                                    onclick="event.stopPropagation(); return confirm('Are you sure you want to delete this error? All reports and comments will be deleted')">
                                                <span class="icon-delete"></span>
                                            </button>
                                            {{ Form::close() }}
                                            @endcan

                                            {{-- TODO: Co z usuwaniem? --}}
                                        </div>
                                    </td>
                                </tr>
                            @endcan
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                {{-- TODO: Przetestować i poprawić szablon --}}
                There aren't any errors created in this project
            @endif

            @include('common.pagination.full', ['paginator' => $errors, 'class' => 'mts'])
        </div>
    </div>
@endsection