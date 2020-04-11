@if(Auth::id() === 1)
    <div class="card mt-3">
        <div class="card-header">
            <h1 class="card-title">Actions</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 align-content-end">
                    @if(!\Request::is('/'))
                        <a class="btn btn-outline-primary" href="{{route('content.upload',['album' => $id])}}">Upload files</a>
                        <a class="btn btn-outline-primary" href="{{route('album.edit', ['id'=>$id])}}">Edit album</a>
                    @endif
                    <a class="btn btn-outline-primary" href="{{route('album.create')}}">Create sub album</a>
                </div>
            </div>
        </div>
    </div>
@endif
