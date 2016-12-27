@extends('layouts.app')
@section('title', 'Create tag')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('projects.index') }}"> Projects </a>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
        <a href="{{ route('projects.tags.index', $project->id) }}"> Tags </a>
        <a href="{{ route('projects.tags.create', $project->id) }}"> Create </a>
    </div>
@endsection

@section('menu')
    @include('projects._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::open('post', route('projects.tags.store', [$project->id])) }}

            <h1 class="mbm"> Create tag </h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('name', 'Name') }}
                {{ Form::string('name') }}
                {{ Form::error('name') }}
            </div>

            <div class="form-field">
                {{ Form::checkbox('custom_rgb', '1', 'Define custom RGB color') }}
            </div>

            <div class="form-field" data-color-format="predefined_hex">
                <div class="row" style="max-width: 300px">
                    <div class="col xs-6">{{ Form::radio('color', 'F44336', '<span class="label" style="background: #F44336"> Red </span>') }}</div>
                    <div class="col xs-6">{{ Form::radio('color', '8BC34A', '<span class="label" style="background: #8BC34A"> Light Green </span>') }}</div>

                    <div class="col xs-6">{{ Form::radio('color', 'E91E63', '<span class="label" style="background: #E91E63"> Pink </span>') }}</div>
                    <div class="col xs-6">{{ Form::radio('color', 'CDDC39', '<span class="label" style="background: #CDDC39"> Lime </span>') }}</div>

                    <div class="col xs-6">{{ Form::radio('color', '9C27B0', '<span class="label" style="background: #9C27B0"> Purple </span>') }}</div>
                    <div class="col xs-6">{{ Form::radio('color', 'FFEB3B', '<span class="label" style="background: #FFEB3B"> Yellow </span>') }}</div>

                    <div class="col xs-6">{{ Form::radio('color', '673AB7', '<span class="label" style="background: #673AB7"> Deep Purple </span>') }}</div>
                    <div class="col xs-6">{{ Form::radio('color', 'FFC107', '<span class="label" style="background: #FFC107"> Amber </span>') }}</div>

                    <div class="col xs-6">{{ Form::radio('color', '3F51B5', '<span class="label" style="background: #3F51B5"> Indigo </span>') }}</div>
                    <div class="col xs-6">{{ Form::radio('color', 'FF9800', '<span class="label" style="background: #FF9800"> Orange </span>') }}</div>

                    <div class="col xs-6">{{ Form::radio('color', '2196F3', '<span class="label" style="background: #2196F3"> Blue </span>') }}</div>
                    <div class="col xs-6">{{ Form::radio('color', 'FF5722', '<span class="label" style="background: #FF5722"> Deep Orange </span>') }}</div>

                    <div class="col xs-6">{{ Form::radio('color', '03A9F4', '<span class="label" style="background: #03A9F4"> Light Blue </span>') }}</div>
                    <div class="col xs-6">{{ Form::radio('color', '795548', '<span class="label" style="background: #795548"> Brown </span>') }}</div>

                    <div class="col xs-6">{{ Form::radio('color', '00BCD4', '<span class="label" style="background: #00BCD4"> Cyan </span>') }}</div>
                    <div class="col xs-6">{{ Form::radio('color', '9E9E9E', '<span class="label" style="background: #9E9E9E"> Grey </span>') }}</div>

                    <div class="col xs-6">{{ Form::radio('color', '009688', '<span class="label" style="background: #009688"> Teal </span>') }}</div>
                    <div class="col xs-6">{{ Form::radio('color', '607D8B', '<span class="label" style="background: #607D8B"> Blue Grey </span>') }}</div>

                    <div class="col xs-6">{{ Form::radio('color', '4CAF50', '<span class="label" style="background: #4CAF50"> Green </span>') }}</div>
                </div>
                {{ Form::error('color') }}
            </div>

            <div class="form-field" data-color-format="custom_rgb" style="display: none;">
                <div class="row -no-gutter" style="max-width: 300px;">
                    <div class="col xs-4">
                        <div class="mrx">
                            {{ Form::label('red', 'Red') }}
                            {{ Form::string('red') }}
                        </div>
                    </div>

                    <div class="col xs-4">
                        <div class="mrx">
                            {{ Form::label('green', 'Green') }}
                            {{ Form::string('green') }}
                        </div>
                    </div>

                    <div class="col xs-4">
                        <div class="mrx">
                            {{ Form::label('blue', 'Blue') }}
                            {{ Form::string('blue') }}
                        </div>
                    </div>
                </div>

                {{ Form::error('red') }}
                {{ Form::error('green') }}
                {{ Form::error('blue') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Create', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('projects.tags.index', $project->id) }}"> Back to list </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection

@section('scripts')
    @parent

    <script>
        $(function() {
            $('input[name="custom_rgb"]').change(function() {
                $('[data-color-format]').hide();

                var format = $(this).is(':checked') ? 'custom_rgb' : 'predefined_hex';
                $('[data-color-format="'+ format +'"]').show();
            }).change();
        })
    </script>
@endsection