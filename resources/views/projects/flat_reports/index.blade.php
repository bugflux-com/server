@extends('layouts.app')
@section('title', 'Unclassified reports for '. $project->name)

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.flat-reports.index', $project->id) }}"> Unclassified reports </a>
    </div>
@endsection

@section('menu')
    @include('projects._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbs">
            <div class="text-right">
                {{-- TODO: Dodać sortowanie i filtrowanie --}}

                @include('common.pagination.simple', ['paginator' => $flat_reports, 'class' => 'mtx mls xs-hide'])
            </div>

            @include('common.alert')

            @if(count($flat_reports)>0)
                <div class="scroll-x mts">
                    {{-- TODO: Dodać kolumny z trendem dla liczby błędów i raportów --}}
                    <table class="table">
                        <colgroup>
                            <col width="1">
                            <col>
                            <col width="100">
                            <col width="1">
                        </colgroup>
                        <thead>
                        <tr>
                            <th> # </th>
                            <th> Name </th>
                            <th> Reported </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($flat_reports as $flat_report)
                            @can('read', [$flat_report, $project])
                                <tr data-href="{{ route('projects.flat-reports.show', [$project->id, $flat_report->id]) }}">
                                    <td>{{ $flat_report->id }}</td>
                                    <td>{{ $flat_report->name }}</td>
                                    <td class="text-nowrap">{{ $flat_report->created_at }}</td>
                                    <td class="text-nowrap">
                                        <div class="table-row-hover">
                                            @can('redo', [$flat_report, $project])
                                                {{ Form::open('post', route('projects.flat-reports.redo', [$project->id, $flat_report->id]), false, ['style'=>"display: inline"]) }}
                                                {{-- TODO: Zmienić onclick na własny modal --}}
                                                <button class="button -small" type="submit"
                                                        title="Redo report">
                                                    <span class="icon-redo"></span>
                                                </button>
                                                {{ Form::close() }}
                                            @endcan

                                            @can('delete', [$flat_report, $project])
                                                {{ Form::open('delete', route('projects.flat-reports.destroy', [$project->id, $flat_report->id]), false, ['style'=>"display: inline"]) }}
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
                <div class="mts"> There are no unclassified reports in this project </div>
            @endif

            @include('common.pagination.full', ['paginator' => $flat_reports, 'class' => 'mts'])
        </div>
    </div>
@endsection