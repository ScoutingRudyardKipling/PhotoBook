@extends('layouts.page')

@section('content')
    @auth
        <h1 class="mt-2">
            {{__('app.Dashboard')}}
        </h1>
        @include('components.actions')
        <h3 class="mt-3">{{__('app.Albums')}}</h3>
        <div class="row">
            @foreach( $albums as $album)
                @include('components.album', ['album' => $album])
            @endforeach
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-xs col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">{{__('app.Dashboard')}}</h1>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <p>
                            {{__('auth.You are not logged in!')}}
                        </p>
                        @if (config('auth.useSol'))
                            <p>
                                {{__('auth.sol-login-or-email-login')}}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-xs-12 col-lg-6 mt-3">
                @include('auth.components.sol-login')
                @if (config('auth.useSol') === false)
                    @include('auth.components.email-login')
                @endif
            </div>
        </div>
    @endauth
@endsection
