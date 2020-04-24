@extends('layouts.page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">{{__('app.action.Create')}} {{__('app.user')}}</h1>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.store') }}">
                        @include('pages.user.fields')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
