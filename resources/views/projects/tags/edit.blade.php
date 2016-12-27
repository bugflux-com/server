@extends('layouts.app')
@section('title', 'Edit tag')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.tags.index', $project->id) }}"> Tags </a>
        <a href="{{ route('projects.tags.edit', [$project->id, $tag->id]) }}"> Edit </a>
    </div>
@endsection

@section('menu')
    @include('projects._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::model('put', route('projects.tags.update', [$tag->project_id, $tag->id]), $tag) }}

            <h1 class="mbm"> Edit tag </h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('name', 'Name') }}
                {{ Form::string('name') }}
                {{ Form::error('name') }}
            </div>

            <div class="form-field">
                {{ Form::label('red', 'Red') }}
                {{ Form::string('red') }}
                {{ Form::error('red') }}
            </div>

            <div class="form-field">
                {{ Form::label('green', 'Green') }}
                {{ Form::string('green') }}
                {{ Form::error('green') }}
            </div>

            <div class="form-field">
                {{ Form::label('blue', 'Blue') }}
                {{ Form::string('blue') }}
                {{ Form::error('blue') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Edit', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('projects.tags.index', $project->id) }}"> Back to list </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection