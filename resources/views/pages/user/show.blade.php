@extends('layouts.page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">{{__('app.action.View')}} {{__('app.user')}} {{$user->name}}</h1>
                </div>
                <div class="card-body">
                    <p><strong>{{__('auth.name')}}: </strong> {{$user->name}} </p>
                    <p><strong>{{__('auth.email')}}:</strong> {{$user->email}} </p>
                    <p><strong>{{__('auth.sol.name')}}:</strong>{{$user->external_user}}</p>
                    <p><strong>{{__('auth.birth date')}}:</strong> {{$user->birth_date}} </p>
                    <p><strong>{{__('auth.gender')}}:</strong> {{$user->gender}} </p>
                    <p><strong>{{__('auth.preferred language')}}:</strong> {{$user->preferred_language}} </p>
                    <p><strong>{{__('app.Role')}}:</strong> {{$user->roles()->first()->name}} </p>
                </div>
                <div class="form-group px-3 pb-1">
                    <a href="javascript:history.back()" class="btn btn-warning float-left">
                        {{__('app.action.Back')}}
                    </a>
                     <a href="{{route('user.edit', ['user' => $user])}}" class="btn btn-outline-primary float-right">
                        {{__('app.action.Edit')}}
                    </a>

                </div>
            </div>

        </div>
    </div>
@endsection
