@extends('layouts.app')
@section('title', 'Edit '. $project->name)

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.edit', $project->id) }}"> Edit </a>
    </div>
@endsection

@section('menu')
    @include('projects._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::model('put', route('projects.update', $project->id), $project) }}

            <h1 class="mbm"> Edit {{ $project->name }}</h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('name') }}
                {{ Form::string('name') }}
                {{ Form::error('name') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Edit', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('projects.errors.index', $project->id) }}"> Back to list </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection