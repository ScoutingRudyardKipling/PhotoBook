@extends('layouts.app')

@section('page')
    <div class="container">
        @include('components.errors')
        @yield('content')
    </div>
@stop
