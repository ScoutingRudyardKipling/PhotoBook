@csrf
<div class="form-group">
    <label for="albumName">Album name</label>
    <input type="text" value="{{$album->name ?? old('name')}}" class="form-control" id="albumName" name="name" placeholder="Zomerkamp">
</div>
<div class="form-group">
    <label for="inputState">Parent</label>
    <select id="inputState" name="parent_id" class="form-control">
        <option
                @if(is_null($album->parent->id ?? old('parent_id')))
                selected
                @endif
                value=""
        >
            Top level
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
