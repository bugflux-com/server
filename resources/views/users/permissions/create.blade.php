@extends('layouts.app')
@section('title', 'Create permission for'. $user->name ?: $user->email)

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('users.index') }}"> Users </a>
        <a href="{{ route('users.show', $user->id) }}">{{ $user->name or $user->email }}</a>
        <a href="{{ route('users.permissions.index', $user->id) }}"> Permissions </a>
        <a href="{{ route('users.permissions.create', $user->id) }}"> Create </a>
    </div>
@endsection

@section('menu')
    @include('users._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::open('post', route('users.permissions.store', $user->id)) }}

            <h1 class="mbm"> Create permission for {{ $user->name or $user->email }} </h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('group_id', 'Group') }}
                {{ Form::select('group_id', $groups) }}
                {{ Form::error('group_id') }}
            </div>

            <div class="form-field">
                {{-- TODO: Zmienić select na lookup'a --}}
                {{ Form::label('project_id', 'Project') }}
                {{ Form::select('project_id', $projects) }}
                {{ Form::error('project_id') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Create', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('users.permissions.index', $user->id) }}"> Back to list </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection