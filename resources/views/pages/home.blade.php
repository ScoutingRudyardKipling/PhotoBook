@extends('layouts.page')

@section('content')
    @auth
        <div class="dashboard-hero">
            <h1>{{ __('app.Dashboard') }}</h1>
            <p>{{ config('app.name') }}</p>
        </div>
        @include('components.actions')
        <p class="section-label">{{ __('app.Albums') }}</p>
        <div class="row">
            @foreach ($albums as $album)
                @include('components.album', ['album' => $album])
            @endforeach
        </div>
    @else
        <div class="row justify-content-center mt-4">
            <div class="col-xs col-lg-8">
                <div class="card login-card">
                    <div class="card-header">
                        <h1 class="card-title">{{ __('app.Dashboard') }}</h1>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <p class="text-muted mb-3">
                            {{ __('auth.You are not logged in!') }}
                        </p>
                        @if (config('auth.useSol'))
                            <p class="text-muted">
                                {{ __('auth.sol-login-or-email-login') }}
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
