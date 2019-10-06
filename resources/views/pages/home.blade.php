@extends('layouts.page')

@section('content')
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
                    @auth
                        You are logged in!
                    @else
                        You are not logged in!
                    @endauth
                </div>
            </div>
        </div>
    </div>
    @auth
        <h2 class="mt-3">Albums</h2>
        <div class="row">
            @foreach( $albums as $album)
                @include('components.album', ['album' => $album])
            @endforeach
        </div>
    @endauth
@endsection