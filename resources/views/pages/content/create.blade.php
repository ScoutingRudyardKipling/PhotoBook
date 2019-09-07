@extends('layouts.page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('content.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="fileWrapper">File</label>
                            <input type="file" class="form-control-file" name="content" id="fileWrapper" aria-describedby="fileHelp">
                            <small id="fileHelp" class="form-text text-muted">Please upload a valid image file. Size of image should not be more than 2MB.</small>
                        </div>
                        <div class="form-group">
                            <label for="inputState">Album</label>
                            <select id="inputState" name="album_id" class="form-control">
                                <option selected value="null">Top level</option>
                                @foreach(\App\Models\Album::all() as $album)
                                    <option value="{{$album->id}}">{{$album->name}}</option>
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
