@extends('layouts.app')
@section('title', 'Permissions for '. $project->name)

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.permissions.index', $project->id) }}"> Permissions </a>
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

                @can('createAny', [\App\Models\Permission::class, $project])
                    <a href="{{ route('projects.permissions.create', $project->id) }}" class="button -small text-middle mtx mls"> Create permission </a>
                @endcan

                @include('common.pagination.simple', ['paginator' => $permissions, 'class' => 'mtx mls xs-hide'])
            </div>

            @include('common.alert')

            @if(count($permissions)>0)
                <div class="scroll-x mts">
                    {{-- TODO: Dodać kolumny z trendem dla liczby błędów i raportów --}}
                    <table class="table">
                        <colgroup>
                            <col width="1">
                            <col>
                            <col>
                            <col width="1">
                        </colgroup>
                        <thead>
                        <tr>
                            <th> # </th>
                            <th> User </th>
                            <th> Group </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permissions as $permission)
                            @can('read', $permission)
                                <tr>
                                    <td>{{ $permission->id }}</td>
                                    <td class="text-nowrap">
                                        <a title="{{ $permission->user->email }}" href="{{ route('users.show', $permission->user->id) }}">
                                            <img class="ellipsis text-middle mrx" src="{{ route('users.photo', [$permission->user->id, 'small']) }}" width="24" height="24">
                                            {{ $permission->user->name or $permission->user->email }}
                                        </a>
                                    </td>
                                    <td class="text-nowrap">{{ $permission->group->name }}</td>
                                    <td class="text-nowrap">
                                        <div class="table-row-hover">
                                            @can('update', $permission)
                                                <a href="{{ route('projects.permissions.edit', [$project->id, $permission->id]) }}"
                                                   class="button -small" title="Edit permission"
                                                   onclick="event.stopPropagation()">
                                                    <span class="icon-mode_edit"></span>
                                                </a>
                                            @endcan
                                            @can('delete', $permission)
                                                {{ Form::open('delete', route('projects.permissions.destroy', [$project->id, $permission->id]), false, ['style' => 'display: inline']) }}
                                                {{-- TODO: Zmienić onclick na własny modal --}}
                                                <button class="button -small" type="submit"
                                                        title="Delete permission"
                                                        onclick="event.stopPropagation(); return confirm('Are you sure you want to delete this permission?')">
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
                There aren't any permissions created in this project
            @endif

            @include('common.pagination.full', ['paginator' => $permissions, 'class' => 'mts'])
        </div>
    </div>
@endsection