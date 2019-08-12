@extends('layouts.page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('album.store') }}">
                        <div class="form-group">
                            <label for="albumName">Album name</label>
                            <input type="text" class="form-control" id="albumName" placeholder="Album name">
                        </div>
                        <input class="btn btn-primary" type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
