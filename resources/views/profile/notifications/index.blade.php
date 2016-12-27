@extends('layouts.app')
@section('title', 'Your notifications')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('profile.edit') }}"> Your profile </a>
        <a href="{{ route('profile.notifications.index') }}"> Notifications </a>
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
                <div class="panel-shrink">
                    {{-- TODO: Dodać sortowanie i filtrowanie? --}}

                    @include('common.pagination.simple', ['paginator' => $notifications, 'class' => 'mbx xs-hide'])
                </div>
            </div>
        </div>
    </div>

    <div class="container fluid">
        <div class="pts pbs">
            @include('common.alert')

            <div class="clearfix">
                @foreach($notifications as $notification)
                    <div class="mts">
                        @include("profile.notifications.messages.{$notification->type->code}-group", [
                            'grouping' => $notification
                        ])
                    </div>
                @endforeach
            </div>

            @include('common.pagination.full', ['paginator' => $notifications, 'class' => 'mts'])
        </div>
    </div>
@endsection