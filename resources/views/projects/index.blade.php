@extends('layouts.app')
@section('title', 'Projects')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
    </div>
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbs">
            <div class="text-right">
				{{-- TODO: Dodać sortowanie i filtrowanie --}}
                {{-- TODO: Dodać przyciski sterujące trendem w tabeli (7D, 30D) --}}

                @can('createAny', \App\Models\Project::class)
                    <a href="{{ route('projects.create') }}" class="button -small text-middle mtx mls"> Create project </a>
                @endcan

                @include('common.pagination.simple', ['paginator' => $projects, 'class' => 'mtx mls xs-hide'])
            </div>

            @include('common.alert')

            @if(count($projects)>0)
                <div class="scroll-x mts">
                    {{-- TODO: Dodać kolumny z trendem dla liczby błędów i raportów --}}
                    <table class="table">
                        <colgroup>
                            <col width="1">
                            <col>
                            <col width="100">
                            <col width="100">
                            <col width="1">
                        </colgroup>
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Name </th>
                                <th> Errors </th>
                                <th> Reports </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                                <tr data-href="{{ route('projects.errors.index', $project->id) }}">
                                    <td>{{ $project->id }}</td>
                                    <td>{{ $project->name }}</td>
                                    <td>{{ $project->errors_count }}</td>
                                    <td>{{ $project->reports_count }}</td>
                                    <td class="text-nowrap">
                                        <div class="table-row-hover">
                                            {{-- TODO: Ukrywać kolumnę na urządzeniach mobilnych? A może zawsze wyświetlać przyciski? --}}
                                            {{-- TODO: Przenieść onclick="event.stopPropagation()" do logiki tabel w js --}}
                                            @can('update', $project)
                                                <a href="{{ route('projects.edit', $project->id) }}"
                                                   class="button -small" title="Edit project"
                                                   onclick="event.stopPropagation()">
                                                    <span class="icon-mode_edit"></span>
                                                </a>
                                            @endcan
                                            
                                            @can('delete', $project)
                                                {{ Form::open('delete', route('projects.destroy', $project->id), false, ['style' => 'display: inline']) }}
                                                {{-- TODO: Zmienić onclick na własny modal --}}
                                                <button class="button -small" type="submit"
                                                        title="Delete project"
                                                        onclick="event.stopPropagation(); return confirm('Are you sure you want to delete this project?')">
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
                You are not assigned to any project
            @endif

            @include('common.pagination.full', ['paginator' => $projects, 'class' => 'mts'])
        </div>
    </div>
    </div>
@endsection