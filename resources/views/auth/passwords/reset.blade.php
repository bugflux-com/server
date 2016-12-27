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
                    {{-- Form to send new password reset link (improve UX) --}}
                    @if($errors->has('email'))
                        {{ Form::open('post', url('password/email'), false, ['id' => 'send-password-reset-link']) }}
                        {{ Form::hidden('email', $email) }}
                        {{ Form::close() }}
                    @endif

                    {{-- Password reset true form --}}
                    {{ Form::open('post', url('password/reset')) }}
                    {{ Form::hidden('token', $token) }}

                    <h1 class="mbx"> Reset password </h1>
                    <p class="mbm"> Type your e-mail and new password for account. </p>

                    @if(session()->has('status'))
                        <div class="mbm">
                            <span class="alert -success">{{ session('status') }}</span>
                        </div>
                    @endif

                    <div class="form-field">
                        {{ Form::label('email', 'E-mail') }}
                        {{ Form::string('email', $email) }}
                        {{ Form::error('email') }}

                        @if($errors->has('email'))
                            {{-- If there is any validation error enable button to send new password reset link --}}
                            <a class="button -small mtx" onclick="$('#send-password-reset-link').submit()"> Send new password reset link </a>
                        @endif
                    </div>

                    <div class="form-field">
                        {{ Form::label('password') }}
                        {{ Form::password('password') }}
                        {{ Form::error('password') }}
                    </div>

                    <div class="form-field">
                        {{ Form::label('password_confirmation') }}
                        {{ Form::password('password_confirmation') }}
                        {{ Form::error('password_confirmation') }}
                    </div>

                    <div class="form-field">
                        {{ Form::submit('Reset password', ['class' => 'mrs']) }}
                        <a class="font-small" href="{{ url('login') }}"> Back to login </a>
                    </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection