@extends('layouts.app')
@section('title', 'Edit comment')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.errors.index', $project->id) }}"> Errors </a>
        <a href="{{ route('projects.errors.show', [$project->id, $error->id]) }}">{{ str_limit($error->name, 48) }}</a>
        <a href="{{ route('projects.errors.comments.index', [$project->id, $error->id]) }}"> Comments </a>
        <a href="{{ route('projects.errors.comments.edit', [$project->id, $error->id, $comment->id]) }}"> Edit </a>
    </div>
@endsection

@section('menu')
    @include('projects.errors._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::model('put', route('projects.errors.comments.update', [$error->project_id, $error->id, $comment->id]), $comment) }}

            <h1 class="mbm"> Edit comment </h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('message') }}
                {{ Form::text('message') }}
                {{ Form::error('message') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Edit', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('projects.errors.comments.index', [$project->id, $error->id]) }}"> Back to comments </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection