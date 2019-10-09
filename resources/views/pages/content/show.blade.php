@extends('layouts.page')

@section('content')
    <h1 class="mt3">
        {{$content->name}}
    </h1>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <img class="card-img img-fluid" src="{{ $content->getUrl()}}" alt="{{$content->name}}"/>
            </div>
        </div>
    </div>
@endsection
