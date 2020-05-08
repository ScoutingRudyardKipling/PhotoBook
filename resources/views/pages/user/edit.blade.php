@extends('layouts.page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">{{__('app.action.Edit')}} {{__('app.user')}} {{ $user->name }}</h1>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.update' , $user->id) }}">
                        @method('PUT')
                        @include('pages.user.fields', ['user' => $user])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
