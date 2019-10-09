@extends('layouts.page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Edit Album {{ $album->name }}</h1>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('album.update' , $album->id) }}">
                        @method('PUT')
                        @include('pages.album.fields', ['album' => $album])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
