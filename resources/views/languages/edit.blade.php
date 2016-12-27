@extends('layouts.app')
@section('title', 'Edit language')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('languages.index') }}"> Languages </a>
        {{-- <a>{{ $language->name }}</a> TODO: wyświetlać czy nie? --}}
        <a href="{{ route('languages.edit', $language->id) }}"> Edit </a>
    </div>
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::model('put', route('languages.update', $language->id), $language) }}

            <h1 class="mbm"> Edit {{ $language->name }}</h1>

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
                {{ Form::submit('Edit', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('languages.index') }}"> Back to list </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection