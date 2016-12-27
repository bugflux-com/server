@extends('layouts.app')
@section('title', 'Mappings for '. $project->name)

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.mappings.index', $project->id) }}"> Mappings </a>
    </div>
@endsection

@section('menu')
    @include('projects._menu')
@endsection

@section('content')
    <div class="toolbar">
        <div class="container fluid">
            <div class="panel ptx">
                <div class="panel-grow prm -bottom">
                    @include('projects.mappings._tabs')
                </div>
                <div class="panel-shrink">
                    @if(!$display_default_mappings)
                        @can('createAny', [\App\Models\Mapping::class, $project])
                            <a href="{{ route('projects.mappings.create', $project->id) }}" class="button -small mbx @if($mappings->hasPages()) mrs @endif"> Create mapping </a>
                        @endcan
                    @endif

                    @include('common.pagination.simple', ['paginator' => $mappings, 'class' => 'mbx xs-hide'])
                </div>
            </div>
        </div>
    </div>

    <div class="container fluid">
        <div class="pbs">
            @include('common.alert')

            @if(count($mappings)>0)
                <div class="scroll-x mts">
                    {{-- TODO: Dodać kolumny z trendem dla liczby błędów i raportów --}}
                    <table class="table">
                        <colgroup>
                            <col width="1">
                            <col>
                            <col>
                            <col>
                            <col width="1">
                        </colgroup>
                        <thead>
                        <tr>
                            <th> # </th>
                            <th> Model</th>
                            <th class="text-nowrap"> Mapped value </th>
                            <th class="text-nowrap"> Mapped to </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($mappings as $mapping)
                            @can('read', $mapping)
                                <tr>
                                    <td>{{ $mapping->id }}</td>
                                    <td>{{ ucfirst($mapping->mappable_type) }}</td>
                                    <td>{{ $mapping->value }}</td>
                                    <td>{{ $mapping->mappable->name }}</td>
                                    <td class="text-nowrap">
                                        <div class="table-row-hover">
                                            @can('update', $mapping)
                                                <a href="{{ route('projects.mappings.edit', [$project->id, $mapping->id]) }}"
                                                   class="button -small" title="Edit mapping"
                                                   onclick="event.stopPropagation()">
                                                    <span class="icon-mode_edit"></span>
                                                </a>
                                            @endcan
                                            @can('delete', $mapping)
                                                {{ Form::open('delete', route('projects.mappings.destroy', [$project->id, $mapping->id]), false, ['style' => 'display: inline']) }}
                                                {{-- TODO: Zmienić onclick na własny modal --}}
                                                <button class="button -small" type="submit"
                                                        title="Delete mapping"
                                                        onclick="event.stopPropagation(); return confirm('Are you sure you want to delete this mapping?')">
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
                <div class="mts"> There aren't any mappings created in this project </div>
            @endif

            @include('common.pagination.full', ['paginator' => $mappings, 'class' => 'mts'])
        </div>
    </div>
@endsection