@extends('layouts.blank')
@section('title', 'Sign in')
@section('bodyClass', 'bg-neutral')

@section('content')
    <div class="container">
        <div class="ptl pbl">
            <div class="panel">
                <div class="panel-shrink -top mrl xs-hide">
                    <a href="http://bugflux.com"><img src="{{ asset('img/icons/manifest.ico') }}" width="128" height="128"></a>
                </div>
                <div class="panel-grow">
                    {{ Form::open('post', url('login')) }}

                    <h1 class="mbm"> Sign in </h1>

                    <div class="form-field">
                        {{ Form::label('email', 'E-mail') }}
                        {{ Form::string('email') }}
                        {{ Form::error('email') }}
                    </div>

                    <div class="form-field">
                        {{ Form::label('password') }}
                        {{ Form::password('password') }}
                        {{ Form::error('password') }}
                    </div>

                    <div class="form-field">
                        {{ Form::checkbox('remember', 1, 'Remember me') }}
                        {{ Form::error('remember') }}
                    </div>

                    <div class="form-field">
                        {{ Form::submit('Sign in', ['class' => 'mrs']) }}
                        <a class="font-small" href="{{ url('password/reset') }}"> Forgot your password? </a>
                    </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection