@extends('layouts.app')
@section('title', 'Create system')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('systems.index') }}"> Systems </a>
        <a href="{{ route('systems.create') }}"> Create </a>
    </div>
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::open('post', route('systems.store')) }}

            <h1 class="mbm"> Create system </h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('name') }}
                {{ Form::string('name') }}
                {{ Form::error('name') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Create', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('systems.index') }}"> Back to list </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection