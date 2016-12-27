@extends('layouts.app')
@section('title', 'Confirm e-mail')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('profile.edit') }}"> Your profile </a>
        <a href="{{ url()->current() }}"> Confirm e-mail </a>
    </div>
@endsection

@section('menu')
    @include('profile._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::open('put', route('profile.confirm.newemail.update', $token)) }}

            <h1 class="mbm"> Confirm e-mail </h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('password_current') }}
                {{ Form::password('password_current') }}
                {{ Form::error('password_current') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Confirm', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('projects.index') }}"> Cancel </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection