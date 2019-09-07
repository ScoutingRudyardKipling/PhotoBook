<div class="col-6 col-md-4 col-lg-3 mt-4">
    @foreach($content->getMedia() as $media)
        <a class="img-link img-lightbox mx-auto d-block" href="{{ $media->getUrl() }}">
            <div class="card">
                <img class="card-img img-fluid" src="{{ $media->getUrl('thumb') }}"/>
                <div class="card-header">{{$content->name}}</div>
            </div>
        </a>
    @endforeach
</div>

