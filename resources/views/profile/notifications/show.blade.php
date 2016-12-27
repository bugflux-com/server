@extends('layouts.app')
@section('title', 'Your notifications')

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('profile.edit') }}"> Your profile </a>
        <a href="{{ route('profile.notifications.index') }}"> Notifications </a>
        <a href="{{ route('profile.notifications.show', $grouping->id) }}"> Details </a>{{-- TODO: Lepsza informacja (typ powiadomienia) --}}
    </div>
@endsection

@section('menu')
    @include('profile._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbs">
            <div class="text-right">
                {{-- TODO: Oznacz jako nieprzeczytane/zakończone --}}

                <a href="{{ route('profile.notifications.index') }}" class="button -small text-middle mtx mls"> Back to list </a>

                @include('common.pagination.simple', ['paginator' => $notifications, 'class' => 'mtx mls xs-hide'])
            </div>

            @include('common.alert')

            <div class="mts">
                @include("profile.notifications.messages.{$grouping->type->code}-group", [
                    'grouping' => $grouping
                ])
            </div>

            <hr class="mts">

            @foreach($notifications as $notification)
                <div class="mts">
                    {{-- TODO: Fajnie byłoby wyświetlać kraj z którego ktoś próbował się dostać (umiemy to bez wykorzystania zewn. api?) --}}
                    {{-- TODO: Dodać zbieranie i wyświetlanie informacje o przeglądarce --}}
                    @include("profile.notifications.messages.{$grouping->type->code}", [
                        'notification' => $notification
                    ])
                </div>
            @endforeach

            @include('common.pagination.full', ['paginator' => $notifications, 'class' => 'mts'])
        </div>
    </div>
@endsection