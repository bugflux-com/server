@extends('layouts.app')
@section('title', 'Edit permission')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.permissions.index', $project->id) }}"> Permissions </a>
        <a href="{{ route('projects.permissions.edit', [$project->id, $permission->id]) }}"> Edit </a>
    </div>
@endsection

@section('menu')
    @include('projects._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::model('put', route('projects.permissions.update', [$permission->project_id, $permission->id, 'previous_url' => $previous_url]), $permission) }}

            <h1 class="mbm"> Edit {{ $permission->user->name or $permission->user->email }} permission </h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('group_id', 'Group') }}
                {{ Form::select('group_id', $groups) }}
                {{ Form::error('group_id') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Edit', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('projects.permissions.index', $project->id) }}"> Back to list </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection