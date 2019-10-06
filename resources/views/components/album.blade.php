<div class="col-6 col-md-4 col-lg-3 mt-4">
    <a href="{{route('album.show', [$album->id])}}">
        <div class="card">
            <img class="card-img-top" src="{{ $album->getFeaturedContent()->getMedia()->first()->getUrl('thumb') }}" alt="{{$album->name}}">
            <div class="card-header">{{$album->name}}</div>
        </div>
    </a>
</div>
