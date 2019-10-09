@csrf

<div class="form-group">
    <label for="inputState">Album</label>
    <select id="inputState" name="parent_id" class="form-control">
        @foreach(\App\Models\Album::all() as $parent)
            <option
                    @if(($content->parent->id ?? old('parent_id')) == $parent->id)
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
