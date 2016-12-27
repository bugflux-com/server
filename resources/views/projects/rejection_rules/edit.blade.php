@extends('layouts.app')
@section('title', 'Edit rejection rule')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.rejection-rules.index', $project->id) }}"> Rejection rules </a>
        <a href="{{ route('projects.rejection-rules.edit', [$project->id, $rejection_rule->id]) }}"> Edit </a>
    </div>
@endsection

@section('menu')
    @include('projects._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::model('put', route('projects.rejection-rules.update', [$rejection_rule->project_id, $rejection_rule->id]), $rejection_rule) }}

            <h1 class="mbm"> Edit rejection rule </h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('description', 'Description') }}
                {{ Form::string('description') }}
                {{ Form::error('description') }}
            </div>

            <hr class="mtm">

            <div class="mtm mbm">
                <p> In fields below you define <b><a href="http://php.net/manual/pl/reference.pcre.pattern.syntax.php"> regex patterns </a></b>. If error report meet all defined patterns (<b>patterns are connected using "and"</b> condition) then report will be permanently discarded. </p>
                <p class="mtx"> For example if <b>version equal "^1."</b> and <b>environment equal "Development"</b> then reports which <b>version starts from string "1." <u>and</u> environment contain word "Development"</b> will be discarded. </p>
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