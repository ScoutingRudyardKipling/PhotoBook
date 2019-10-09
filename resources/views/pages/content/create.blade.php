@extends('layouts.page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Create Content</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('content.store') }}" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="fileWrapper">File</label>
                            <input type="file" class="form-control-file" name="content" id="fileWrapper" aria-describedby="fileHelp">
                            <small id="fileHelp" class="form-text text-muted">Please upload a valid image file. Size of image should not be more than 2MB.</small>
                        </div>
                        @include('pages.content.fields')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
