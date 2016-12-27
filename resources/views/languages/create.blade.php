@extends('layouts.app')
@section('title', 'Create language')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('languages.index') }}"> Languages </a>
        <a href="{{ route('languages.create') }}"> Create </a>
    </div>
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::open('post', route('languages.store')) }}

            <h1 class="mbm"> Create language </h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('code') }}
                {{ Form::string('code') }}
                {{ Form::error('code') }}
            </div>

            <div class="form-field">
                {{ Form::label('name') }}
                {{ Form::string('name') }}
                {{ Form::error('name') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Create', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('languages.index') }}"> Back to list </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection