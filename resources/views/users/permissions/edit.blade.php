@extends('layouts.app')
@section('title', 'Edit permission for'. $user->name ?: $user->email)

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('users.index') }}"> Users </a>
        <a href="{{ route('users.show', $user->id) }}">{{ $user->name or $user->email }}</a>
        <a href="{{ route('users.permissions.index', $user->id) }}"> Permissions </a>
        <a href="{{ route('users.permissions.edit', [$user->id, $permission->id]) }}"> Edit </a>
    </div>
@endsection

@section('menu')
    @include('users._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::model('put', route('projects.permissions.update', [$permission->project_id, $permission->id, 'previous_url' => $previous_url]), $permission) }}

            <h1 class="mbm"> Edit {{ $user->name or $user->email }}'s permission in project {{ $permission->project->name }}</h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('group_id', 'Group') }}
                {{ Form::select('group_id', $groups) }}
                {{ Form::error('group_id') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Edit', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('users.permissions.index', $user->id) }}"> Back to list </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection