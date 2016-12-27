@extends('layouts.app')
@section('title', 'Rejection rules for '. $project->name)

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.rejection-rules.index', $project->id) }}"> Rejection rules </a>
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

                @can('createAny', [\App\Models\RejectionRule::class, $project])
                    <a href="{{ route('projects.rejection-rules.create', $project->id) }}" class="button -small text-middle mtx mls"> Create rejection rule </a>
                @endcan

                @include('common.pagination.simple', ['paginator' => $rejection_rules, 'class' => 'mtx mls xs-hide'])
            </div>

            @include('common.alert')

            @if(count($rejection_rules)>0)
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
                            <th> Description </th>
                            <th> Expressions </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($rejection_rules as $rejection_rule)
                            {{-- TODO: logika sprawdzania uprawnien do przeniesienia do kontrolera --}}
                            @can('read', $rejection_rule)
                                <tr>
                                    <td>{{ $rejection_rule->id }}</td>
                                    <td>{{ $rejection_rule->description }}</td>
                                    <td>
                                        @php($_first = true)
                                        @foreach($rejection_rule->filled as $key => $value)
                                            @if(!$_first)
                                                <br> and
                                            @endif
                                            <b>{{ $key }}</b> match <b>{{ $value }}</b>

                                            @php($_first = false)
                                        @endforeach
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="table-row-hover">
                                            @can('update', $rejection_rule)
                                                <a href="{{ route('projects.rejection-rules.edit', [$project->id, $rejection_rule->id]) }}"
                                                   class="button -small" title="Edit rejection rule"
                                                   onclick="event.stopPropagation()">
                                                    <span class="icon-mode_edit"></span>
                                                </a>
                                            @endcan
                                            @can('delete', $rejection_rule)
                                                {{ Form::open('delete', route('projects.rejection-rules.destroy', [$project->id, $rejection_rule->id]), false, ['style' => 'display: inline']) }}
                                                {{-- TODO: Zmienić onclick na własny modal --}}
                                                <button class="button -small" type="submit"
                                                        title="Delete rejection rule"
                                                        onclick="event.stopPropagation(); return confirm('Are you sure you want to delete this rejection rule?')">
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
                <div class="mts"> There aren't any rejection rules created in this project </div>
            @endif

            @include('common.pagination.full', ['paginator' => $rejection_rules, 'class' => 'mts'])
        </div>
    </div>
@endsection