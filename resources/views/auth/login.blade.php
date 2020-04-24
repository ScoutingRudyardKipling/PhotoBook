@extends('layouts.app')

@section('page')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
{{--                @include('auth.components.sol-login')--}}
{{--            </div>--}}
{{--            <div class="col-md-8 mt-3">--}}
                @include('auth.components.email-login')
            </div>
        </div>
    </div>
@endsection
