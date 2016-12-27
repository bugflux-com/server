@extends('layouts.app')
@section('title', $project->name)

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
    </div>
@endsection

@section('menu')
    @include('projects._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbs">
            <div class="text-right">
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project->id) }}" class="button -small mtx"> Edit </a>
                @endcan

                @can('delete', $project)
                    {{ Form::open('delete', route('projects.destroy', $project->id), false, ['class' => 'iblock mtx']) }}
                    {{-- TODO: Zmienić onclick na własny modal --}}
                    <button class="button -small" type="submit"
                            onclick="return confirm('Are you sure you want to delete this project?')">
                        Delete </button>
                    {{ Form::close() }}
                @endcan
            </div>

            @include('common.alert')

            <div class="row">
                <div class="col xs-12 lg-4 mts">
                    <div class="scroll-x">
                        <table class="table -no-border">
                            <colgroup>
                                <col width="100">
                                <col>
                            </colgroup>
                            <tr>
                                <th> Id </th>
                                <td>{{ $project->id }}</td>
                            </tr>
                            <tr>
                                <th class="text-top"> Name </th>
                                <td>{{ $project->name }}</td>
                            </tr>
                            <tr>
                                <th> Code </th>
                                <td>{{ $project->code }}</td>
                            </tr>
                            <tr>
                                <th> Errors </th>
                                <td>{{ $project->errors()->count() }}</td>
                            </tr>
                            <tr>
                                <th> Reports </th>
                                <td>{{ $project->reports()->count() }}</td>
                            </tr>
                            @if(!empty($project->latestError))
                                <tr>
                                    <th class="text-top"> Latest error </th>
                                    <td>
                                        <a title="{{ $project->latestError->name }}" href="{{ route('projects.errors.reports.index', [$project->id, $project->latestError->id]) }}">
                                            {{ str_limit($project->latestError->name, 50) }}
                                        </a>
                                        <div class="font-light font-small" title="{{ $project->latestError->created_at }}">
                                            {{ $project->latestError->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                <div class="col xs-12 lg-8 mts">
                    <canvas id="trend-chart" height="250"></canvas>
                </div>
            </div>

            <hr class="mts">

            <div class="row">
                <div class="col xs-12 md-4 mts">
                    <canvas id="top3-versions" height="160"></canvas>
                </div>
                <div class="col xs-12 md-4 mts">
                    <canvas id="top3-systems" height="160"></canvas>
                </div>
                <div class="col xs-12 md-4 mts">
                    <canvas id="top3-languages" height="160"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent

    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/chart.js') }}"></script>
    @include('projects.charts.incoming_reports_per_day', ['canvas_id' => 'trend-chart', 'reports' => $project->reportsTrend, 'period' => $trend_for_days])
    @include('projects.charts.total_reports_per_version', ['canvas_id' => 'top3-versions', 'reports' => $project->reportsPerVersion])
    @include('projects.charts.total_reports_per_system', ['canvas_id' => 'top3-systems', 'reports' => $project->reportsPerSystem])
    @include('projects.charts.total_reports_per_language', ['canvas_id' => 'top3-languages', 'reports' => $project->reportsPerLanguage])
@endsection