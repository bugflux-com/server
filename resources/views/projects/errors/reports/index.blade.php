@extends('layouts.app')
@section('title', 'Reports')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.errors.index', $project->id) }}"> Errors </a>
        <a href="{{ route('projects.errors.show', [$project->id, $error->id]) }}">{{ str_limit($error->name, 48) }}</a>
        <a href="{{ route('projects.errors.reports.index', [$project->id, $error->id]) }}"> Reports </a>
    </div>
@endsection

@section('menu')
    @include('projects.errors._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbx">
            <div class="text-right">
                {{ Form::open('get', route('projects.errors.reports.index', [$project->id, $error->id]), false, ['class' => 'iblock'], false) }}

                <span class="input-group text-top mtx mrs">
                    {{ Form::select('system', $system_names_ids, $system, ['class' => '-small', 'submit-on-change' => true] ) }}
                    {{ Form::select('language', $language_names_ids, $language, ['class' => '-small', 'submit-on-change' => true] ) }}
                    {{ Form::select('version', $version_names_ids, $version, ['class' => '-small', 'submit-on-change' => true] ) }}
                </span>

                {{ Form::close() }}
                <span class="input-group mtx @if($reports->hasPages()) mrs @endif">
                    <div class="dropdown">
                        <div class="dropdown-link">
                            <span class="icon-notifications"></span><span class="icon-arrow_drop_down"></span>
                        </div>
                        <div class="dropdown-menu">
                            <div class="font-normal mls mrs mts mbs text-left" style="min-width: 200px; max-width: 100%">
                                {{ Form::open('put', route('profile.notifications.reports.update', $error->id)) }}

                                <div class="form-field">
                                    {{ Form::label('new_report', 'New report') }}
                                    <div class="font-small font-neutral mbx"> Inform about new reports for this error. </div>

                                    <div>
                                        <span class="iblock mrm">
                                            {{ Form::hidden('new_report.internal', 0) }}
                                            {{ Form::checkbox('new_report.internal', 1, "Internal", $notifications->pivot && $notifications->pivot->internal) }}
                                        </span>
                                        <span class="iblock">
                                            {{ Form::hidden('new_report.email', 0) }}
                                            {{ Form::checkbox('new_report.email', 1, "E-mail", $notifications->pivot && $notifications->pivot->email) }}
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

                @include('common.pagination.simple', ['paginator' => $reports, 'class' => 'mtx xs-hide'])
            </div>

            @include('common.alert')

            @if(count($reports)>0)
                <div class="scroll-x mts">
                    <table class="table">
                        <colgroup>
                            <col width="1">
                            <col>
                            <col>
                            <col>
                            <col>
                            <col width="1">
                            <col width="100">
                            <col width="1">
                        </colgroup>
                        <thead>
                        <tr>
                            <th> # </th>
                            <th> System </th>
                            <th> Language </th>
                            <th> Version </th>
                            <th> Message </th>
                            <th></th>
                            <th> Reported </th>
                            <th></th>
                            {{-- TODO: Liczba komentarzy? --}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reports as $report)
                            {{-- TODO: Przenieść logikę sprawdzania uprawnien do kontrolera --}}
                            @can('read', $error)
                                <tr data-href="{{ route('projects.errors.reports.show', [$project->id, $error->id, $report->id]) }}">
                                    <td>{{ $report->id }}</td>
                                    <td class="text-nowrap">{{ $report->system->name }}</td>
                                    <td class="text-nowrap">{{ $report->language->name }}</td>
                                    <td class="text-nowrap">{{ $report->version->name }}</td>
                                    <td>{{ str_limit($report->message, 64) }}</td>
                                    <td class="text-nowrap">
                                        @if($report->comments_count > 0)
                                            <a href="{{ route('projects.errors.reports.comments.index', [$project->id, $error->id, $report->id]) }}">
                                                <span class="icon-question_answer"></span>
                                                <span class="font-small">{{ $report->comments_count }}</span>
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        <span title="{{ $report->reported_at }} ({{ $report->created_at }})">
                                            {{ $report->reported_at->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="table-row-hover">
                                            {{-- TODO: Ukrywać kolumnę na urządzeniach mobilnych? A może zawsze wyświetlać przyciski? --}}
                                            {{-- TODO: Przenieść onclick="event.stopPropagation()" do logiki tabel w js --}}
                                            @can('delete', $report)
                                                {{ Form::open('delete', route('projects.errors.reports.destroy', [$project->id, $error->id, $report->id]), false, ['style' => 'display: inline']) }}
                                                {{-- TODO: Zmienić onclick na własny modal --}}
                                                <button class="button -small" type="submit"
                                                        title="Delete report"
                                                        onclick="event.stopPropagation(); return confirm('Are you sure you want to delete this report?')">
                                                    <span class="icon-delete"></span>
                                                </button>
                                                {{ Form::close() }}
                                            @endcan
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
                There aren't any reports created for this error.
            @endif

            @include('common.pagination.full', ['paginator' => $reports, 'class' => 'mts'])
        </div>
    </div>
@endsection