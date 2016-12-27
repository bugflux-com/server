@extends('layouts.app')
@section('title', 'Users')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('users.index') }}"> Users </a>
    </div>
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbs">
            <div class="text-right">
                {{-- TODO: Dodać sortowanie i filtrowanie --}}
                {{-- TODO: Dodać przyciski sterujące trendem w tabeli (7D, 30D) --}}

                @can('createAny', \App\Models\User::class)
                    <a href="{{ route('users.create') }}" class="button -small text-middle mtx mls"> Create user </a>
                @endcan

                @include('common.pagination.simple', ['paginator' => $users, 'class' => 'mtx mls xs-hide'])
            </div>

            @include('common.alert')

            <div class="scroll-x mts">
                <table class="table">
                    <colgroup>
                        @if(Auth::user()->is_root)
                            <col width="1">
                        @endif
                        <col>
                        <col width="100">
                        <col width="1">
                    </colgroup>
                    <thead>
                        <tr>
                            @if(Auth::user()->is_root)
                                <th> # </th>
                            @endif
                            <th> Name </th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            {{-- FIXME: Wyrzucić logikę sprawdzania uprawnien (@can) do kontrolera (dokleić do zapytania przed wywołaniem paginate) --}}
                            @can('read', $user)
                                <tr @can('read', $user) data-href="{{ route('users.show', $user->id) }}" @endcan>
                                    @if(Auth::user()->is_root)
                                        <td>{{ $user->id }}</td>
                                    @endif
                                    <td class="text-nowrap">
                                        <a title="{{ $user->email }}" href="{{ route('users.show', $user->id) }}">
                                            <img class="ellipsis text-middle mrx" src="{{ route('users.photo', [$user->id, 'small']) }}" width="24" height="24">
                                            {{ $user->name or $user->email }}
                                        </a>
                                    </td>
                                    <td>
                                        @if(Auth::user()->is_root && $user->is_root)
                                            <span class="label -info"> Admin </span>
                                        @endif

                                        @if($user->is_blocked)
                                            <span class="label -error"> Blocked </span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="table-row-hover">
                                            {{-- TODO: Ukrywać kolumnę na urządzeniach mobilnych? A może zawsze wyświetlać przyciski? --}}
                                            {{-- TODO: Przenieść onclick="event.stopPropagation()" do logiki tabel w js --}}
                                            @can('update', $user)
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                   class="button -small" title="Edit user account"
                                                   onclick="event.stopPropagation()">
                                                    <span class="icon-mode_edit"></span>
                                                </a>
                                            @endcan
                                            @can('delete', $user)
                                                {{ Form::open('delete', route('users.destroy', $user->id), false, ['style' => 'display: inline']) }}
                                                {{-- TODO: Zmienić onclick na własny modal --}}
                                                <button class="button -small" type="submit"
                                                        title="Delete user account permanently"
                                                        onclick="event.stopPropagation(); return confirm('Are you sure you want to delete permanently this user?')">
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

            @include('common.pagination.full', ['paginator' => $users, 'class' => 'mts'])
        </div>
    </div>
@endsection