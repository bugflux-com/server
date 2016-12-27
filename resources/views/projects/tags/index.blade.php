@extends('layouts.app')
@section('title', 'Tags for '. $project->name)

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.tags.index', $project->id) }}"> Tags </a>
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

                @can('createAny', [\App\Models\Tag::class, $project])
                    <a href="{{ route('projects.tags.create', $project->id) }}" class="button -small text-middle mtx mls"> Create tag </a>
                @endcan

                @include('common.pagination.simple', ['paginator' => $tags, 'class' => 'mtx mls xs-hide'])
            </div>

            @include('common.alert')

            @if(count($tags)>0)
                <div class="scroll-x mts">
                    {{-- TODO: Dodać kolumny z trendem dla liczby błędów i raportów --}}
                    <table class="table">
                        <colgroup>
                            <col width="1">
                            <col>
                            <col width="100">
                            <col width="1">
                        </colgroup>
                        <thead>
                        <tr>
                            <th> # </th>
                            <th> Name </th>
                            <th title="Number of errors with assigned tag"> Errors </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tags as $tag)
                            @can('read', $tag)
                                <tr data-href="{{ route('projects.errors.index', [$project->id, 'tag' => $tag->id]) }}">
                                    <td>{{ $tag->id }}</td>
                                    <td>
                                        <span class="label" style="background: {{ $tag->hex }}">
                                            {{ $tag->name }}
                                        </span>
                                    </td>
                                    <td>{{ $tag->errors_count }}</td>
                                    <td class="text-nowrap">
                                        <div class="table-row-hover">
                                            @can('update', $tag)
                                                <a href="{{ route('projects.tags.edit', [$project->id, $tag->id]) }}"
                                                   class="button -small" title="Edit tag"
                                                   onclick="event.stopPropagation()">
                                                    <span class="icon-mode_edit"></span>
                                                </a>
                                            @endcan
                                            @can('delete', $tag)
                                                {{ Form::open('delete', route('projects.tags.destroy', [$project->id, $tag->id]), false, ['style' => 'display: inline']) }}
                                                {{-- TODO: Zmienić onclick na własny modal --}}
                                                <button class="button -small" type="submit"
                                                        title="Delete tag permanently"
                                                        onclick="event.stopPropagation(); return confirm('Are you sure you want to delete permanently this tag?')">
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
                <div class="mts"> There aren't any tags created in this project </div>
            @endif

            @include('common.pagination.full', ['paginator' => $tags, 'class' => 'mts'])
        </div>
    </div>
@endsection