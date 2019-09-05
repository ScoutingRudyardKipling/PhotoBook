<div class="col-6 col-md-4 col-lg-3 mt-4">
    <div class="card text-white">
        @foreach($content->getMedia() as $media)
            <img class="card-img" src="{{ $media->getUrl() }}" alt="{{$content->name}}">
        @endforeach
        <div class="card-header">{{$content->name}}</div>
    </div>
</div>
