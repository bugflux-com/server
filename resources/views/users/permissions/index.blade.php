@extends('layouts.app')
@section('title', 'Permissions for '. $user->name ?: $user->email)

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('users.index') }}"> Users </a>
        <a href="{{ route('users.show', $user->id) }}">{{ $user->name or $user->email }}</a>
        <a href="{{ route('users.permissions.index', $user->id) }}"> Permissions </a>
    </div>
@endsection

@section('menu')
    @include('users._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbs">
            <div class="text-right">
                {{-- TODO: Dodać sortowanie i filtrowanie? --}}

                @can('createAnyPermission', \App\Models\Permission::class)
                    <a href="{{ route('users.permissions.create', $user->id) }}" class="button -small text-middle mtx mls"> Create permission </a>
                @endcan

                @include('common.pagination.simple', ['paginator' => $permissions, 'class' => 'mtx mls xs-hide'])
            </div>

            @include('common.alert')

            <div class="scroll-x mts">
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
                            <th> Project </th>
                            <th> Group </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                            {{-- TODO: Trzeba zbadać czy potrzebne i ewentualnie przenieść do logiki kontrolera (przed paginate) --}}
                            @can('read', $permission)
                                <tr>
                                    <td>{{ $permission->id }}</td>
                                    <td>{{ $permission->project->name }}</td>
                                    <td>{{ $permission->group->name }}</td>
                                    <td class="text-nowrap">
                                        <div class="table-row-hover">
                                            @can('update', $permission)
                                                <a href="{{ route('users.permissions.edit', [$permission->user_id, $permission->id, 'previous_url' => url()->current()]) }}"
                                                   class="button -small" title="Edit user permissions"
                                                   onclick="event.stopPropagation()">
                                                    <span class="icon-mode_edit"></span>
                                                </a>
                                            @endcan
                                            @can('delete', $permission)
                                                {{ Form::open('delete', route('projects.permissions.destroy', [$permission->project_id, $permission->id]), false, ['style' => 'display: inline']) }}
                                                {{-- TODO: Zmienić onclick na własny modal --}}
                                                <button class="button -small" type="submit"
                                                        title="Delete user permission"
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

            @include('common.pagination.full', ['paginator' => $permissions, 'class' => 'mts'])
        </div>
    </div>
@endsection