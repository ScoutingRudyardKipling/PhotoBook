<div class="col-6 col-md-4 col-lg-3 mt-4">
    <a href="{{route('album.show', [$album->id])}}">
        <div class="card">
            @empty ($album->getFeaturedContentThumb())
                <img class="card-img-top" src="/img/logo.png" alt="{{$album->name}}">
            @else
                <img class="card-img-top" src="{{ $album->getFeaturedContentThumb() }}" alt="{{$album->name}}">
            @endempty
            <div class="card-header">{{$album->name}}</div>
        </div>
    </a>
</div>
