@extends('layouts.page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Edit Content {{ $content->name }}</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('content.update', $content->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        <div class="form-group">
                            <label for="contentName">Content name</label>
                            <input type="text" value="{{$content->name ?? old('name')}}" class="form-control" id="contentName" name="name" placeholder="Groepsfoto">
                        </div>
                        @include('pages.content.fields', ['content' => $content])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
