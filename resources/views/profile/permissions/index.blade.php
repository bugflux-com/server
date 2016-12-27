@extends('layouts.app')
@section('title', 'Your permissions')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('profile.edit') }}"> Your profile </a>
        <a href="{{ route('profile.permissions.index') }}"> Permissions </a>
    </div>
@endsection

@section('menu')
    @include('profile._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbs">
            <div class="text-right">
                {{-- TODO: Dodać sortowanie i filtrowanie? --}}

                @include('common.pagination.simple', ['paginator' => $permissions, 'class' => 'mtx mls xs-hide'])
            </div>

            @include('common.alert')

            <div class="scroll-x mts">
                <table class="table">
                    <colgroup>
                        <col width="500">
                        <col width="500">
                        <col width="150">
                    </colgroup>
                    <thead>
                    <tr>
                        <th> Project </th>
                        <th> Group </th>
                        <th> Updated </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($permissions as $permission)
                        {{-- TODO: Trzeba zbadać czy potrzebne i ewentualnie przenieść do logiki kontrolera (przed paginate) --}}
                        @can('read', $permission)
                            <tr>
                                <td>
                                    <a href="{{ route('projects.errors.index', $permission->project->id) }}">
                                        {{ $permission->project->name }}
                                    </a>
                                </td>
                                <td class="text-nowrap">{{ $permission->group->name }}</td>
                                <td class="text-nowrap">
                                    <span title="{{ $permission->updated_at }}">
                                        {{ $permission->updated_at->diffForHumans() }}
                                    </span>
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