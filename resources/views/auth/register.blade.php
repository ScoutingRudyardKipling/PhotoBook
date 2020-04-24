@extends('layouts.page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('auth.components.email-register')
        </div>
    </div>
@endsection
