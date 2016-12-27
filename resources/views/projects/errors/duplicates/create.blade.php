@extends('layouts.app')
@section('title', 'Mark as duplicate')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.errors.index', $project->id) }}"> Errors </a>
        <a href="{{ route('projects.errors.show', [$project->id, $error->id]) }}">{{ str_limit($error->name, 48) }}</a>
        <a href="{{ route('projects.errors.duplicates.index', [$project->id, $error->id]) }}"> Duplicates </a>
        <a href="{{ route('projects.errors.duplicates.create', [$project->id, $error->id]) }}"> Create </a>
    </div>
@endsection

@section('menu')
    @include('projects.errors._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::open('post', route('projects.errors.duplicates.store', [$project->id, $error->id])) }}

            <h1 class="mbm"> Mark as duplicate </h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('error_id', 'Error') }}
                {{ Form::select('error_id', $errors) }}
                {{ Form::error('error_id') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Mark', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('projects.errors.duplicates.index', [$project->id, $error->id]) }}"> Back to list </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection