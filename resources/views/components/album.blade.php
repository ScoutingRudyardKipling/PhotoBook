<div class="col-6 col-md-4 col-lg-3 mt-4">
    <a href="{{ route('album.show', [$album->id]) }}" class="album-card-link">
        <div class="album-card">
            <div class="album-thumb-wrapper">
                @empty ($album->getFeaturedContentThumb())
                    <img src="/img/logo.png" alt="{{ $album->name }}">
                @else
                    <img src="{{ $album->getFeaturedContentThumb() }}" alt="{{ $album->name }}">
                @endempty
            </div>
            <div class="album-card-name">{{ $album->name }}</div>
        </div>
    </a>
</div>
