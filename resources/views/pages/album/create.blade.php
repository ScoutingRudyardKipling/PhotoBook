@extends('layouts.page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('album.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="albumName">Album name</label>
                            <input type="text" class="form-control" id="albumName" name="name" placeholder="Zomerkamp">
                        </div>
                        <div class="form-group">
                            <label for="inputState">Parent</label>
                            <select id="inputState" name="parent_id" class="form-control">
                                <option selected>Top level</option>
                                @foreach(\App\Models\Album::all() as $parent)
                                    <option value="{{$parent->id}}">{{$parent->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input class="btn btn-primary" type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
