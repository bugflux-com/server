@extends('layouts.app')
@section('title', 'Languages')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('languages.index') }}"> Languages </a>
    </div>
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbs">
            <div class="text-right">
                {{-- TODO: Dodać sortowanie i filtrowanie --}}

                @can('createAny', \App\Models\Language::class)
                    <a href="{{ route('languages.create') }}" class="button -small text-middle mtx mls"> Create language </a>
                @endcan

                @include('common.pagination.simple', ['paginator' => $languages, 'class' => 'mtx mls xs-hide'])
            </div>

            @include('common.alert')

            <div class="scroll-x mts">
                <table class="table">
                    <colgroup>
                        <col width="1">
                        <col width="1">
                        <col>
                        <col width="1">
                    </colgroup>
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Code </th>
                            <th> Name </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($languages as $language)
                            @can('read', $language)
                                <tr>
                                    <td>{{ $language->id }}</td>
                                    <td class="text-nowrap">{{ $language->code }}</td>
                                    <td>{{ $language->name }}</td>
                                    <td class="text-nowrap">
                                        <div class="table-row-hover">
                                            {{-- TODO: Przenieść onclick="event.stopPropagation()" do logiki tabel w js --}}
                                            @can('update', $language)
                                                <a href="{{ route('languages.edit', $language->id) }}"
                                                   class="button -small" title="Edit language"
                                                   onclick="event.stopPropagation()">
                                                    <span class="icon-mode_edit"></span>
                                                </a>
                                            @endcan
                                            @can('delete', $language)
                                                {{ Form::open('delete', route('languages.destroy', $language->id), false, ['style' => 'display: inline']) }}
                                                {{-- TODO: Zmienić onclick na własny modal --}}
                                                <button class="button -small" type="submit"
                                                        title="Delete language permanently"
                                                        onclick="event.stopPropagation(); return confirm('Are you sure you want to delete permanently this language?')">
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

            @include('common.pagination.full', ['paginator' => $languages, 'class' => 'mts'])
        </div>
    </div>
@endsection