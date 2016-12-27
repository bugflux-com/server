@extends('layouts.app')
@section('title', $report->name)

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.errors.index', $project->id) }}"> Errors </a>
        <a href="{{ route('projects.errors.show', [$project->id, $error->id]) }}">{{ str_limit($error->name, 48) }}</a>
        <a href="{{ route('projects.errors.reports.index', [$project->id, $error->id]) }}"> Reports </a>
        <a href="{{ route('projects.errors.reports.show', [$project->id, $error->id, $report->id]) }}">{{ str_limit($report->name, 48) }}</a>
    </div>
@endsection

@section('menu')
    @include('projects.errors.reports._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbs">
            <div class="text-right">
                @can('delete', $project)
                    {{ Form::open('delete', route('projects.errors.reports.destroy', [$project->id, $report->error_id, $report->id]), false, ['style' => 'display: inline']) }}
                    {{-- TODO: Zmienić onclick na własny modal --}}
                    <button class="button -small mtx" type="submit"
                            onclick="event.stopPropagation(); return confirm('Are you sure you want to delete this report?')">
                        Delete
                    </button>
                    {{ Form::close() }}
                @endcan
            </div>

            @include('common.alert')

            <div class="scroll-x mts">
                <table class="table -no-border">
                    <colgroup>
                        <col width="100">
                        <col>
                    </colgroup>
                    <tr>
                        <th> Id </th>
                        <td>{{ $report->id }}</td>
                    </tr>
                    <tr>
                        <th> Client id </th>
                        <td>
                            <span title="{{ $report->client_id }}">
                                {{ str_limit($report->client_id, 20) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th> Client ip </th>
                        <td>{{ $report->client_ip }}</td>
                    </tr>
                    <tr>
                        <th> System </th>
                        <td>{{ $report->system->name }}</td>
                    </tr>
                    <tr>
                        <th> Environment </th>
                        <td>{{ $report->error->environment->name }}</td>
                    </tr>
                    <tr>
                        <th> Language </th>
                        <td>{{ $report->language->name }} ({{ $report->language->code }})</td>
                    </tr>
                    <tr>
                        <th> Version </th>
                        <td>{{ $report->version->name }}</td>
                    </tr>
                    <tr>
                        <th class="text-top"> Name </th>
                        <td>{{ $report->name }}</td>
                    </tr>
                    <tr>
                        <th> Reported </th>
                        <td>
                            <span title="{{ $report->reported_at }}">
                                {{ $report->reported_at->diffForHumans() }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            @if(!empty($report->message))
                <hr class="mts">
                <div class="scroll-x mts">
                    <strong> Message </strong>
                    <div class="mtx">{!! nl2br(e($report->message)) !!}</div>
                </div>
            @endif

            <hr class="mts">
            <div class="scroll-x mts text-nowrap">
                <strong> Stack trace </strong>
                <div class="mtx">{!! nl2br(e($report->stack_trace)) !!}</div>
            </div>

            {{-- TODO: Dodać więcej informacji, np. ostatnia aktywność. Aktywność typowo związana z raportem --}}
        </div>
    </div>
@endsection