@extends('layouts.app')
@section('title', 'Reports')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.errors.index', $project->id) }}"> Errors </a>
        <a href="{{ route('projects.errors.show', [$project->id, $error->id]) }}">{{ str_limit($error->name, 48) }}</a>
        <a href="{{ route('projects.errors.duplicates.index', [$project->id, $error->id]) }}"> Duplicates </a>
    </div>
@endsection

@section('menu')
    @include('projects.errors._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbs">
            <div class="text-right">
                {{-- TODO: Dodać sortowanie i filtrowanie --}}
                {{-- TODO: Dodać przyciski sterujące trendem w tabeli (7D, 30D) --}}

                @can('update', $error)
                    <a href="{{ route('projects.errors.duplicates.create', [$error->project_id, $error->id]) }}" class="button -small text-middle mtx mls"> Mark as duplicate </a>
                @endcan

                @include('common.pagination.simple', ['paginator' => $duplicates, 'class' => 'mtx mls xs-hide'])
            </div>

            @include('common.alert')

            @if(count($duplicates)>0)
                <div class="scroll-x mts">
                    <table class="table">
                        <colgroup>
                            <col width="1">
                            <col>
                            <col width="1">
                        </colgroup>
                        <thead>
                        <tr>
                            <th> # </th>
                            <th> Error </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($duplicates as $duplicate)
                            <tr>
                                <td>{{ $duplicate->id }}</td>
                                <td>
                                    <a href="{{ route('projects.errors.reports.index', [$project->id, $duplicate->error_id]) }}">
                                        {{ str_limit($duplicate->error->name, 150) }}
                                    </a>
                                </td>
                                <td class="text-nowrap">
                                    <div class="table-row-hover">
                                        {{-- TODO: Ukrywać kolumnę na urządzeniach mobilnych? A może zawsze wyświetlać przyciski? --}}
                                        {{-- TODO: Przenieść onclick="event.stopPropagation()" do logiki tabel w js --}}
                                        @can('delete', $error)
                                            {{ Form::open('delete', route('projects.errors.duplicates.destroy', [$error->project_id, $error->id, $duplicate->id]), false, ['style' => 'display: inline']) }}
                                            {{-- TODO: Zmienić onclick na własny modal --}}
                                            <button class="button -small" type="submit"
                                                    title="Delete duplicate"
                                                    onclick="event.stopPropagation(); return confirm('Are you sure you want to delete this duplicate?')">
                                                <span class="icon-delete"></span>
                                            </button>
                                            {{ Form::close() }}
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                {{-- TODO: Przetestować i poprawić szablon --}}
                <div class="pts"> There aren't any duplicates reported for this error. </div>
            @endif

            @include('common.pagination.full', ['paginator' => $duplicates, 'class' => 'mts'])
        </div>
    </div>
@endsection