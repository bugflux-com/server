@extends('layouts.app')
@section('title', 'Your notifications')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('profile.edit') }}"> Your profile </a>
        <a href="{{ route('profile.notifications.index') }}"> Notifications </a>
        <a href="{{ route('profile.notifications.edit') }}"> Settings </a>
    </div>
@endsection

@section('menu')
    @include('profile._menu')
@endsection

@section('content')
    <div class="toolbar">
        <div class="container fluid">
            <div class="panel ptx">
                <div class="panel-grow prm -bottom">
                    @include('profile.notifications._tabs')
                </div>
            </div>
        </div>
    </div>

    <div class="container fluid">
        <div class="pts pbs">
            @include('common.alert')

            <div class="clearfix mts">
                {{ Form::model('put', route('profile.update.notifications'), $notifications) }}

                <div class="form-field">
                    {{ Form::label('invalid_login_attempt', 'Invalid login attempt') }}
                    <div class="font-small font-neutral mbx"> Inform when someone type your e-mail address and wrong password in sign up form. </div>

                    <div>
                        <span class="iblock mrm">
                            {{ Form::hidden('invalid_login_attempt.internal', 0) }}
                            {{ Form::checkbox('invalid_login_attempt.internal', 1, 'Internal') }}
                        </span>
                        <span class="iblock">
                            {{ Form::hidden('invalid_login_attempt.email', 0) }}
                            {{ Form::checkbox('invalid_login_attempt.email', 1, 'E-mail', ['class' => 'iblock']) }}
                        </span>
                    </div>
                </div>

                <div class="form-field pts">
                    {{ Form::label('invalid_login_attempt', 'New permission') }}
                    <div class="font-small font-neutral mbx"> Inform about new access to the project. </div>

                    <div>
                        <span class="iblock mrm">
                            {{ Form::hidden('new_privilege.internal', 0) }}
                            {{ Form::checkbox('new_privilege.internal', 1, 'Internal') }}
                        </span>
                        <span class="iblock">
                            {{ Form::hidden('new_privilege.email', 0) }}
                            {{ Form::checkbox('new_privilege.email', 1, 'E-mail') }}
                        </span>
                    </div>
                </div>

                <div class="form-field pts">
                    {{ Form::label('invalid_login_attempt', 'Changed permission') }}

                    <div>
                        <span class="iblock mrm">
                            {{ Form::hidden('changed_privilege.internal', 0) }}
                            {{ Form::checkbox('changed_privilege.internal', 1, 'Internal') }}
                        </span>
                        <span class="iblock">
                            {{ Form::hidden('changed_privilege.email', 0) }}
                            {{ Form::checkbox('changed_privilege.email', 1, 'E-mail') }}
                        </span>
                    </div>
                </div>

                <div class="form-field pts">
                    {{ Form::submit() }}
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection