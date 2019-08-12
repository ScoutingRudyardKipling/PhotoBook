@extends('layouts.page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('content.store') }}">
                        <div class="form-group">
                            <input type="file" class="custom-file-input form-control" id="file-upload" required>
                            <label class="custom-file-label" for="file-upload">Choose file...</label>
                            <div class="invalid-feedback">Please select a file.</div>
                        </div>
                        <input class="btn btn-primary" type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
