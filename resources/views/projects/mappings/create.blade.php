@extends('layouts.app')
@section('title', 'Create mapping')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.mappings.index', $project->id) }}"> Mappings </a>
        <a href="{{ route('projects.mappings.create', $project->id) }}"> Create </a>
    </div>
@endsection

@section('menu')
    @include('projects._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::open('post', route('projects.mappings.store', [$project->id])) }}

            <h1 class="mbm"> Create mapping </h1>

            @include('common.alert')

            <div class="form-field" id="type">
                {{ Form::label('type', 'Model') }}
                {{ Form::select('type', $mapping_types) }}
                {{ Form::error('type') }}
            </div>

            <div class="form-field">
                {{ Form::label('value', 'Mapped value') }}
                {{ Form::string('value') }}
                {{ Form::error('value') }}
            </div>

            <div class="form-field types" id="system">
                {{ Form::label('system', 'Mapped to') }}
                {{ Form::select('system', $systems) }}
                {{ Form::error('system') }}
            </div>

            <div class="form-field types" id="language" style="display: none;">
                {{ Form::label('language', 'Mapped to') }}
                {{ Form::select('language', $languages) }}
                {{ Form::error('language') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Create', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('projects.mappings.index', $project->id) }}"> Back to list </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection

@section('scripts')
    @parent()

    <script>
        $(function() {
            $('#type select').change(function(){
                $('.types').hide();
                $('#' + $(this).val()).show();
            }).change();
        });
    </script>
@endsection