@extends('layouts.app')
@section('title', 'Your profile')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('profile.edit') }}"> Your profile </a>
    </div>
@endsection

@section('menu')
    @include('profile._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pts pbs">
            @include('common.alert')

            <div class="row">
                <div class="col xs-12 md-2 max-200">
                    <strong> Name </strong>
                </div>
                <div class="col xs-12 md-10">
                    {{ Form::open('put', route('profile.update.name')) }}

                    <div class="form-field">
                        {{ Form::label('current_name') }}
                        {{ Form::string('current_name', Auth::user()->name, ['disabled' => 'disabled']) }}
                    </div>

                    <div class="form-field">
                        {{ Form::label('name', 'New name') }}
                        {{ Form::string('name') }}
                        {{ Form::error('name') }}
                        <div class="font-small font-neutral mtx"> Name is displayed in such places like comment's author or users list. </div>
                    </div>

                    <div class="form-field">
                        {{ Form::submit('Change name') }}
                    </div>

                    {{ Form::close() }}
                </div>
            </div>

            <hr class="mtm mbm">

            <div class="row">
                <div class="col xs-12 md-2 max-200">
                    <strong> Password </strong>
                </div>
                <div class="col xs-12 md-10">
                    {{ Form::open('put', route('profile.update.password')) }}

                    <div class="form-field">
                        {{ Form::label('password_current') }}
                        {{ Form::password('password_current') }}
                        {{ Form::error('password_current') }}
                    </div>

                    <div class="form-field">
                        {{ Form::label('password', trans('form.labels.new_password')) }}
                        {{ Form::password('password') }}
                        {{ Form::error('password') }}
                    </div>

                    <div class="form-field">
                        {{ Form::label('password_confirmation') }}
                        {{ Form::password('password_confirmation') }}
                    </div>

                    <div class="form-field">
                        {{ Form::submit('Change password') }}
                    </div>

                    {{ Form::close() }}
                </div>
            </div>

            <hr class="mtm mbm">

            <div class="row">
                <div class="col xs-12 md-2 max-200">
                    <strong> E-mail </strong>
                </div>
                <div class="col xs-12 md-10">
                    {{ Form::open('put', route('profile.update.email')) }}

                    <div class="form-field">
                        {{ Form::label('current_email') }}
                        {{ Form::string('current_email', Auth::user()->email, ['disabled' => 'disabled']) }}
                    </div>

                    <div class="form-field">
                        {{ Form::label('email', trans('form.labels.new_email')) }}
                        {{ Form::string('email') }}
                        {{ Form::error('email') }}
                        <div class="font-small font-neutral mtx"> You receive confirmation link to this address. </div>
                    </div>

                    <div class="form-field">
                        {{ Form::submit('Change e-mail') }}
                    </div>

                    {{ Form::close() }}
                </div>
            </div>

            <hr class="mtm mbm">

            <div class="row">
                <div class="col xs-12 md-2 max-200">
                    <strong> Photo </strong>
                </div>
                <div class="col xs-12 md-10">
                    {{-- Form for deleting photo --}}
                    {{ Form::open('delete', route('profile.destroy.photo'), false, ['id' => 'profile-delete-photo']) }}
                    {{ Form::close() }}

                    {{-- Form for changing photo --}}
                    {{ Form::open('put', route('profile.update.photo'), true) }}

                    <div class="form-field">
                        {{ Form::label('photo') }}
                        {{ Form::image('photo', route('users.photo', [Auth::user()->id, 'large'])) }}
                        {{ Form::error('photo') }}
                    </div>

                    <div class="form-field">
                        {{ Form::submit('Change photo') }}
                        <a class="button" onclick="$('#profile-delete-photo').submit()"> Delete photo </a>
                    </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection