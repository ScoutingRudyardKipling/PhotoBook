@csrf
<div class="mb-3">
    <label for="albumName">{{__('app.Album')}} {{__('app.name')}}</label>
    <input type="text" value="{{$album->name ?? old('name')}}" class="form-control" id="albumName" name="name" placeholder="{{__('app.Summer camp')}}">
</div>
@if(!empty($album))
    <div class="mb-3">
        <label for="featured">{{__('app.Featured')}}</label>
        <select id="featured" name="featured-select" class="form-control">
            @php($oldFeaturedString = $album->featured_type . '-' . $album->featured_id)
            @foreach($album->childAlbums as $featuredAlbum)
                <option
                        @if($oldFeaturedString === ('App\Models\Album-' . $featuredAlbum->id))
                        selected
                        @endif
                        value="Album-{{$featuredAlbum->id}}"
                >
                    {{__('app.Album')}}: {{$featuredAlbum->name}}
                </option>
            @endforeach
            @foreach($album->contents as $featuredContent)
                <option
                        @if($oldFeaturedString === ('App\Models\Content-' . $featuredContent->id))
                        selected
                        @endif
                        value="Content-{{$featuredContent->id}}"
                >
                    {{__('app.Content')}}: {{$featuredContent->name}}
                </option>
            @endforeach
        </select>
    </div>
@endif
<input type="hidden" value="{{$parent_id}}" id="parent_id" name="parent_id">
@if(!empty($modal))
    <button type="button" class="btn btn-warning float-start" data-bs-dismiss="modal">{{ __('app.action.Cancel') }}</button>
@else
    @include('components.backbutton')
@endif
<input class="btn btn-outline-primary float-end" type="submit" value="{{__('app.action.Submit')}}">
@section('scripts')
    <script>
    $(function () {
      $('#featured').select2({
        placeholder: 'Kies een placeholder...'
      });
    });
    </script>
@endsection
