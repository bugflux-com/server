@extends('layouts.app')
@section('title', $user->name ?: $user->email)

@section('breadcrumb')
    <div class="breadcrumb nav-content-breadcrumb">
        <a href="{{ route('users.index') }}"> Users </a>
        <a href="{{ route('users.show', $user->id) }}">{{ $user->name or $user->email }}</a>
    </div>
@endsection

@section('menu')
    @include('users._menu')
@endsection

@section('content')
    <div class="container fluid">
        <div class="pbs">
            <div class="text-right">
                @can('update', $user)
                    <a href="{{ route('users.edit', $user->id) }}" class="button -small text-middle mtx mls"> Edit </a>
                @endcan

                @can('delete', $user)
                    {{ Form::open('delete', route('users.destroy', $user->id), false, ['class' => 'iblock text-middle mtx']) }}
                        {{-- TODO: Zmienić onclick na własny modal --}}
                        <button class="button -small" type="submit"
                            onclick="return confirm('Are you sure you want to delete permanently this user?')">
                        Delete </button>
                    {{ Form::close() }}
                @endcan
            </div>

            @include('common.alert')

            <div class="panel">
                <div class="panel-grow">
                    <div class="scroll-x mts">
                        <table class="table -no-border">
                            <colgroup>
                                <col width="100">
                                <col>
                            </colgroup>
                            <tr>
                                <th> Id </th>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th> Name </th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th> E-mail </th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            @if(Auth::user()->is_root)
                                <tr>
                                    <th> Created </th>
                                    <td>
                                        <span title="{{ $user->created_at }}">
                                            {{ $user->created_at->diffForHumans() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th> Updated </th>
                                    <td>
                                        <span title="{{ $user->updated_at }}">
                                            {{ $user->updated_at->diffForHumans() }}
                                        </span>
                                    </td>
                                </tr>
                            @endif
                            @if((Auth::user()->is_root && $user->is_root) || $user->is_blocked)
                                <tr>
                                    <th> Status </th>
                                    <td>
                                        @if(Auth::user()->is_root && $user->is_root)
                                            <span class="label -info"> Admin </span>
                                        @endif

                                        @if($user->is_blocked)
                                            <span class="label -error"> Blocked </span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                @if($user->hasPhoto('large'))
                    <div class="panel-shrink -top xs-hide sm-hide">
                        <img class="mtm ellipsis text-middle mll"
                             src="{{ route('users.photo', [$user->id, 'large']) }}"
                             width="{{ config('profile.photo_sizes.large') }}"
                             height="{{ config('profile.photo_sizes.large') }}">
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection