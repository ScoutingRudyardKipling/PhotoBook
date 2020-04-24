@extends('layouts.app')

@section('page')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('auth.components.email-register')
            </div>
        </div>
    </div>
@endsection
