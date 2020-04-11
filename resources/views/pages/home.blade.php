@extends('layouts.page')

@section('content')
    @auth
        <h1 class="mt-2">
            Home
        </h1>
        @include('components.actions')
        <h3 class="mt-3">Albums</h3>
        <div class="row">
            @foreach( $albums as $album)
                @include('components.album', ['album' => $album])
            @endforeach
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        You are not logged in!
                    </div>
                </div>
            </div>
        </div>
    @endauth
@endsection
