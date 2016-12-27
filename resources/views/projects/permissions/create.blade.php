@extends('layouts.app')
@section('title', 'Create permission')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.permissions.index', $project->id) }}"> Permissions </a>
        <a href="{{ route('projects.permissions.create', $project->id) }}"> Create </a>
    </div>
@endsection

@section('menu')
    @include('projects._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::open('post', route('projects.permissions.store', [$project->id])) }}

            <h1 class="mbm"> Create permission </h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('email', 'Email') }}
                {{ Form::string('email') }}
                {{ Form::error('email') }}
            </div>

            <div class="form-field">
                {{ Form::label('group_id', 'Group') }}
                {{ Form::select('group_id', $groups) }}
                {{ Form::error('group_id') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Create', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('projects.permissions.index', $project->id) }}"> Back to list </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection