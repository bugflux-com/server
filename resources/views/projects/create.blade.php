@extends('layouts.app')
@section('title', 'Create project')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.create') }}"> Create </a>
    </div>
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::open('post', route('projects.store')) }}

            <h1 class="mbm"> Create project </h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('name') }}
                {{ Form::string('name') }}
                {{ Form::error('name') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Create', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('projects.index') }}"> Back to list </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection