@extends('layouts.blank')
@section('title', 'Reset password')
@section('bodyClass', 'bg-neutral')

<!-- Main Content -->
@section('content')
    <div class="container">
        <div class="ptl pbl">
            <div class="panel">
                <div class="panel-shrink -top mrl xs-hide">
                    <a href="http://bugflux.com"><img src="{{ asset('img/icons/manifest.ico') }}" width="128" height="128"></a>
                </div>
                <div class="panel-grow">
                    {{ Form::open('post', url('password/email')) }}

                    <h1 class="mbx"> Reset password </h1>
                    <p class="mbm"> Type your e-email in input below to receive message with link to change password. </p>

                    @if(session()->has('status'))
                        <div class="mbm">
                            <span class="alert -success">{{ session('status') }}</span>
                        </div>
                    @endif

                    <div class="form-field">
                        {{ Form::label('email', 'E-mail') }}
                        {{ Form::string('email') }}
                        {{ Form::error('email') }}
                    </div>

                    <div class="form-field">
                        {{ Form::submit('Send password reset link', ['class' => 'mrs']) }}
                        <a class="font-small" href="{{ url('login') }}"> Back to login </a>
                    </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
