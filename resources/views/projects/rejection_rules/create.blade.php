@extends('layouts.app')
@section('title', 'Create rejection rule')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.rejection-rules.index', $project->id) }}"> Rejection rules </a>
        <a href="{{ route('projects.rejection-rules.create', $project->id) }}"> Create </a>
    </div>
@endsection

@section('menu')
    @include('projects._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::open('post', route('projects.rejection-rules.store', [$project->id])) }}

            <h1 class="mbm"> Create rejection rule </h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('description', 'Description') }}
                {{ Form::string('description') }}
                {{ Form::error('description') }}
            </div>

            <hr class="mtm">

            <div class="mtm mbm">
                <p> In fields below you define <b><a href="http://php.net/manual/pl/reference.pcre.pattern.syntax.php"> regex patterns </a></b>. If error report meet all defined patterns (<b>patterns are combined using "and"</b> condition) then report will be rejected. </p>
                <p class="mtx"> For example if pattern for <b>version equals "^1\.."</b> and pattern for <b>environment equals "Development"</b> then reports which <b>version starts with string "1." (like "1.0", "1.8.3") <u>and</u> environment contains word "Development"</b> they all will be discarded. </p>
            </div>

            @foreach ($rejection_fields as $key => $value)
                <div class="form-field">
                    {{ Form::label($value, $key) }}
                    {{ Form::string($value) }}
                    {{ Form::error($value) }}
                </div>
            @endforeach

            <div class="form-field">
                {{ Form::submit('Create', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('projects.rejection-rules.index', $project->id) }}"> Back to list </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection