@extends('layouts.app')
@section('title', 'Edit '. ($user->name ?: $user->email))

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('users.index') }}"> Users </a>
        <a href="{{ route('users.show', $user->id) }}">{{ $user->name or $user->email }}</a>
        <a href="{{ route('users.edit', $user->id) }}"> Edit </a>
    </div>
@endsection

@section('menu')
    @include('users._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::model('put', route('users.update', $user->id), $user) }}

            <h1 class="mbm"> Edit {{ $user->name or $user->email }}</h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('name') }}
                {{ Form::string('name') }}
                {{ Form::error('name') }}
            </div>

            <div class="form-field">
                {{ Form::label('email') }}
                {{ Form::string('email') }}
                {{ Form::error('email') }}
            </div>

            <div class="form-field">
                {{ Form::label('password') }}
                {{ Form::password('password') }}
                {{ Form::error('password') }}
                <div class="font-small font-neutral mtx"> Password won't be changed if you left this field and the field below empty. </div>
            </div>

            <div class="form-field">
                {{ Form::label('password_confirmation') }}
                {{ Form::password('password_confirmation') }}
                {{ Form::error('password_confirmation') }}
            </div>

            <div class="form-field">
                {{ Form::hidden('is_blocked', 0) }}
                {{ Form::checkbox('is_blocked', 1) }}
                {{ Form::error('is_blocked') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Edit', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('users.show', $user->id) }}"> Back to profile </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection