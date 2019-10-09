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
                        @include('pages.content.fields')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
