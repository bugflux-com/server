@extends('layouts.app')
@section('title', $error->name)

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.errors.index', $project->id) }}"> Errors </a>
        <a href="{{ route('projects.errors.show', [$project->id, $error->id]) }}">{{ str_limit($error->name, 48) }}</a>
    </div>
@endsection

@section('menu')
    @include('projects.errors._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbs">
            <div class="text-right">
                @can('update', $project)
                    <a href="{{ route('projects.errors.edit', [$error->project_id, $error->id]) }}" class="button -small mtx"> Edit </a>
                @endcan
            </div>

            @include('common.alert')

            <div class="scroll-x mts">
                <table class="table -no-border">
                    <colgroup>
                        <col width="100">
                        <col>
                    </colgroup>
                    <tr>
                        <th> Id </th>
                        <td>{{ $error->id }}</td>
                    </tr>
                    <tr>
                        <th> Name </th>
                        <td>{{ $error->name }}</td>
                    </tr>
                    <tr>
                        <th> Hash </th>
                        <td>
                            <span title="{{ $error->hash }}">
                                {{ str_limit($error->hash, 20) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th> Environment </th>
                        <td>{{ $error->environment->name }}</td>
                    </tr>
                    @can('readAny', [\App\Models\Tag::class, $error->project])
                        <tr>
                            <th class="text-top"> Tags </th>
                            <td>
                                @foreach($error->tags as $tag)
                                    @cannot('disconnectWithTag', $error)
                                        <span class="label text-middle" style="background: {{ $tag->hex }}">
                                            {{ $tag->name }}
                                        </span>
                                    @endcannot

                                    @can('disconnectWithTag', $error)
                                        {{ Form::open('delete', route('projects.errors.tags.destroy', [$error->project_id, $error->id, $tag->id]), false, ['class' => 'iblock mtx']) }}
                                        {{-- TODO: Zmienić onclick na własny modal --}}
                                        <button class="label text-middle" style="background: {{ $tag->hex }}; cursor: pointer" type="submit"
                                                onclick="return confirm('Are you sure you want to delete this tag from error?')">
                                            {{ $tag->name }} <span class="icon-close"></span>
                                        </button>
                                        {{ Form::close() }}
                                    @endcan
                                @endforeach

                                @can('connectWithTag', $error)
                                    @if(count($tags)>0)
                                        <div class="mtx">
                                            {{ Form::open('post', route('projects.errors.tags.store', [$project->id, $error->id])) }}

                                            <span class="iblock text-top">
                                                {{ Form::select('tag_id', $tags, null, ['class' => '-small text-top']) }}
                                            </span>
                                            {{ Form::submit('<span class="icon-add"></span>', ['class' => 'button -small']) }}
                                            {{ Form::error('tag_id') }}

                                            {{ Form::close() }}
                                        </div>
                                    @elseif(count($error->tags) == 0)
                                        <p> If you want to add tags to this error, you should first <a class="button -small" href="{{ route('projects.tags.create', $project->id) }}"> create some tags </a></p>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endcan
                </table>
            </div>

            {{-- TODO: Dodać więcej informacji, np. ostatnia aktywność. Aktywność typowo związana z błędem --}}
        </div>
    </div>
@endsection