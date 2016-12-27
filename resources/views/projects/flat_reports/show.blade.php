@extends('layouts.app')
@section('title', $flat_report->name)

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.flat-reports.index', $project->id) }}"> Unclassified reports </a>
        <a href="{{ route('projects.flat-reports.show', [$project->id, $flat_report->id]) }}">{{ str_limit($flat_report->name, 30) }}</a>
    </div>
@endsection

@section('menu')
    @include('projects._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbs">
            <div class="text-right">
                {{-- TODO: Oznacz jako nieprzeczytane/zakończone --}}

                <a href="{{ route('projects.flat-reports.index', $project->id) }}" class="button -small text-middle mtx mls"> Back to list </a>
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
                        <td>{{ $flat_report->id }}</td>
                    </tr>
                    <tr>
                        <th> Name </th>
                        <td>{{ $flat_report->name }}</td>
                    </tr>
                    <tr>
                        <th> Version </th>
                        <td>{{ $flat_report->version }}</td>
                    </tr>
                    <tr>
                        <th> System </th>
                        <td>{{ $flat_report->system }}</td>
                    </tr>
                    <tr>
                        <th> Language </th>
                        <td>{{ $flat_report->language }}</td>
                    </tr>
                    <tr>
                        <th> Hash </th>
                        <td>
                            <span title="{{ $flat_report->hash }}">
                                {{ str_limit($flat_report->hash, 20) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th> Environment </th>
                        <td>{{ $flat_report->environment }}</td>
                    </tr>
                    <tr>
                        <th> Client id </th>
                        <td>
                            <span title="{{ $flat_report->client_id }}">
                                {{ str_limit($flat_report->client_id, 20) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th> Client ip </th>
                        <td>{{ $flat_report->client_ip }}</td>
                    </tr>
                    <tr>
                        <th> Created </th>
                        <td>
                            <span title="{{ $flat_report->created_at }}">
                                {{ $flat_report->created_at->diffForHumans() }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th> Updated </th>
                        <td>
                            <span title="{{ $flat_report->updated_at }}">
                                {{ $flat_report->updated_at->diffForHumans() }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            @if(!empty($flat_report->message))
                <hr class="mts">
                <div class="scroll-x mts">
                    <strong> Message </strong>
                    <div class="mtx">{!! nl2br(e($flat_report->message)) !!}</div>
                </div>
            @endif

            <hr class="mts">
            <div class="scroll-x mts text-nowrap">
                <strong> Stack trace </strong>
                <div class="mtx">{!! nl2br(e($flat_report->stack_trace)) !!}</div>
            </div>

            <hr class="mts">
            <div class="mts">
                @can('redo', [$flat_report, $project])
                    {{ Form::open('post', route('projects.flat-reports.redo', [$project->id, $flat_report->id]), false, ['style'=>"display: inline"]) }}
                    <button class="button" type="submit"> Redo </button>
                    {{ Form::close() }}
                @endcan

                @can('delete', [$flat_report, $project])
                    {{ Form::open('delete', route('projects.flat-reports.destroy', [$project->id, $flat_report->id]), false, ['style'=>"display: inline"]) }}
                    {{-- TODO: Zmienić onclick na własny modal --}}
                    <button class="button" type="submit"
                            onclick="event.stopPropagation(); return confirm('Are you sure you want to delete this report?')">
                        Delete
                    </button>
                    {{ Form::close() }}
                @endcan
            </div>
        </div>
    </div>
@endsection