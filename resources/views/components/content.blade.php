<div class="col-6 col-md-4 col-lg-3 mt-4">
    <a class="img-link img-lightbox mx-auto d-block" href="{{ $content->getUrl() }}">
        <div class="card">
            <img class="card-img img-fluid" src="{{ $content->getUrl('thumb')}}" alt="{{$content->name}}"/>
            <div class="card-header">{{$content->name}}</div>
        </div>
    </a>
</div>

