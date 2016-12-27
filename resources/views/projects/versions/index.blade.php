@extends('layouts.app')
@section('title', 'Versions for '. $project->name)

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.versions.index', $project->id) }}"> Versions </a>
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

                @can('createAny', [\App\Models\Version::class, $project])
                    <a href="{{ route('projects.versions.create', $project->id) }}" class="button -small text-middle mtx mls"> Create version </a>
                @endcan

                @include('common.pagination.simple', ['paginator' => $versions, 'class' => 'mtx mls xs-hide'])
            </div>

            @include('common.alert')

            @if(count($versions)>0)
                <div class="scroll-x mts">
                    {{-- TODO: Dodać kolumny z trendem dla liczby błędów i raportów --}}
                    <table class="table">
                        <colgroup>
                            <col width="1">
                            <col>
                            <col width="1">
                        </colgroup>
                        <thead>
                        <tr>
                            <th> # </th>
                            <th> Name </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($versions as $version)
                            {{-- TODO: Przenieść logikę sprawdzania uprawnien do kontrolera --}}
                            @can('read', $version)
                                <tr>
                                    <td>{{ $version->id }}</td>
                                    <td>{{ $version->name }}</td>
                                    <td class="text-nowrap">
                                        <div class="table-row-hover">
                                            @can('update', $version)
                                                <a href="{{ route('projects.versions.edit', [$project->id, $version->id]) }}"
                                                   class="button -small" title="Edit version"
                                                   onclick="event.stopPropagation()">
                                                    <span class="icon-mode_edit"></span>
                                                </a>
                                            @endcan
                                            @can('delete', $version)
                                                {{ Form::open('delete', route('projects.versions.destroy', [$project->id, $version->id]), false, ['style' => 'display: inline']) }}
                                                {{-- TODO: Zmienić onclick na własny modal --}}
                                                <button class="button -small" type="submit"
                                                        title="Delete version permanently"
                                                        onclick="event.stopPropagation(); return confirm('Are you sure you want to delete permanently this version?')">
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
                <div class="mts"> There aren't any versions created for this project </div>
            @endif

            @include('common.pagination.full', ['paginator' => $versions, 'class' => 'mts'])
        </div>
    </div>
@endsection