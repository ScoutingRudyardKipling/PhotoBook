@csrf
<div class="form-group">
    <label for="albumName">{{__('app.Album')}} {{__('app.name')}}</label>
    <input type="text" value="{{$album->name ?? old('name')}}" class="form-control" id="albumName" name="name" placeholder="{{__('app.Summer camp')}}">
</div>
<div class="form-group">
    <label for="inputState">{{__('app.Parent')}} {{__('app.album')}}</label>
    <select id="inputState" name="parent_id" class="form-control">
        <option
                @if(is_null($album->parent->id ?? old('parent_id')))
                selected
                @endif
                value=""
        >
            {{__('app.Top level')}}
        </option>
        @foreach(\App\Models\Album::all() as $parent)
            <option
                    @if(($album->parent->id ?? old('parent_id')) == $parent->id)
                    selected
                    @endif
                    value="{{$parent->id}}"
            >
                {{$parent->name}}
            </option>
        @endforeach
    </select>
</div>
<input class="btn btn-primary" type="submit" value="Submit">
