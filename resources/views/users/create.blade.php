@extends('layouts.app')
@section('title', 'Create user')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('users.index') }}"> Users </a>
        <a href="{{ route('users.create') }}"> Create </a>
    </div>
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::open('post', route('users.store')) }}

            <h1 class="mbm"> Create user </h1>

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
            </div>

            <div class="form-field">
                {{ Form::label('password_confirmation') }}
                {{ Form::password('password_confirmation') }}
                {{ Form::error('password_confirmation') }}
            </div>

            <div class="form-field">
                {{ Form::hidden('is_blocked', 0) }}
                {{ Form::checkbox('is_blocked', 1) }}
                {{-- Form::label('is_blocked') --}}
                {{ Form::error('is_blocked') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Create', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('users.index') }}"> Back to list </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection