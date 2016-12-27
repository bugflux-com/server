@extends('layouts.app')
@section('title', 'Systems')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('systems.index') }}"> Systems </a>
    </div>
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbs">
            <div class="text-right">
                {{-- TODO: Dodać sortowanie i filtrowanie --}}

                @can('createAny', \App\Models\System::class)
                    <a href="{{ route('systems.create') }}" class="button -small text-middle mtx mls"> Create system </a>
                @endcan

                @include('common.pagination.simple', ['paginator' => $systems, 'class' => 'mtx mls xs-hide'])
            </div>

            @include('common.alert')

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
                            <th> Name </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($systems as $system)
                            {{-- TODO: Sprawdzić czy potrzebne i przenieść logikę do kontrolera --}}
                            @can('read', $system)
                                <tr>
                                    <td>{{ $system->id }}</td>
                                    <td>{{ $system->name }}</td>
                                    <td class="text-nowrap">
                                        <div class="table-row-hover">
                                            {{-- TODO: Przenieść onclick="event.stopPropagation()" do logiki tabel w js --}}
                                            @can('update', $system)
                                                <a href="{{ route('systems.edit', $system->id) }}"
                                                   class="button -small" title="Edit system"
                                                   onclick="event.stopPropagation()">
                                                    <span class="icon-mode_edit"></span>
                                                </a>
                                            @endcan
                                            @can('delete', $system)
                                                {{ Form::open('delete', route('systems.destroy', $system->id), false, ['style' => 'display: inline']) }}
                                                {{-- TODO: Zmienić onclick na własny modal --}}
                                                <button class="button -small" type="submit"
                                                        title="Delete system permanently"
                                                        onclick="event.stopPropagation(); return confirm('Are you sure you want to delete permanently this system?')">
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

            @include('common.pagination.full', ['paginator' => $systems, 'class' => 'mts'])
        </div>
    </div>
@endsection