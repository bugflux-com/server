@extends('layouts.app')
@section('title', 'Edit system')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('systems.index') }}"> Systems </a>
        {{-- <a>{{ $system->name }}</a> TODO: wyświetlać czy nie? --}}
        <a href="{{ route('systems.edit', $system->id) }}"> Edit </a>
    </div>
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            {{ Form::model('put', route('systems.update', $system->id), $system) }}

            <h1 class="mbm"> Edit {{ $system->name }}</h1>

            @include('common.alert')

            <div class="form-field">
                {{ Form::label('name') }}
                {{ Form::string('name') }}
                {{ Form::error('name') }}
            </div>

            <div class="form-field">
                {{ Form::submit('Edit', ['class' => 'mrs']) }}
                <a class="font-small" href="{{ route('systems.index') }}"> Back to list </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection
















@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading"> {{ $system->name }} edit </div>
            <div class="panel-body">
                {{ Form::model('put', route('systems.update', [$system->id]), $system) }}

                <div class="form-group">
                    {{ Form::label('name') }}
                    {{ Form::string('name') }}
                    {{ Form::error('name') }}
                </div>

                {{ Form::submit() }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection