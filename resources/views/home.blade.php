@extends('layouts.app')

@section('content')
    <div class="container">
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

                        You are logged in!
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach( $items as $item)
                <div class="col-6 col-md-4 col-lg-3 mt-3">
                    <div class="card">
                        <img class="card-img-top" src="{{ asset('img/350X240.jpg') }}" alt="Card image cap">
                        <div class="card-header">Groep</div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            @foreach( $items as $item)
                <div class="col-6 col-md-4 col-lg-3 mt-3">
                    <div class="card text-white">
                        <img class="card-img" src="{{ asset('img/350X240.jpg') }}" alt="Card image">
                        <div class="card-img-overlay">
                            <h5 class="card-title">Card title</h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
