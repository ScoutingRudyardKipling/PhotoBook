@csrf
<div class="form-group">
    <label for="name">{{__('app.User')}} {{__('app.name')}}</label>
    <input type="text" value="{{$user->name ?? old('name')}}" class="form-control" id="name" name="name" placeholder="{{__('app.name')}}">
</div>
<div class="form-group">
    <label for="email">{{__('auth.email')}}</label>
    <input type="text" value="{{$user->email ?? old('email')}}" class="form-control" id="email" name="email" placeholder="{{__('auth.email')}}">
</div>
<div class="form-group">
    <label for="gender">{{__('auth.gender')}}</label>
    <select id="gender" name="gender" class="form-control">
        <option
                @if(($user->gender ?? old('gender')) == "M")
                selected
                @endif
                value="M"
        >
            {{__('auth.genders.M')}}
        </option>
        <option
                @if(($user->gender ?? old('gender')) == "V")
                selected
                @endif
                value="V"
        >
            {{__('auth.genders.V')}}
        </option>
        <option
                @if(($user->gender ?? old('gender')) == "U")
                selected
                @endif
                value="U"
        >
            {{__('auth.genders.U')}}
        </option>
    </select>
</div>
<div class="form-group">
    <label for="email">{{__('auth.birth date')}}</label>
    <input type="text" value="{{$user->birth_date ?? old('birth_date')}}" class="form-control" id="birth_date" name="birth_date" placeholder="{{__('auth.birth date')}}">
</div>
<div class="form-group">
    <label for="preferred_language">{{__('auth.preferred language')}}</label>
    <select id="preferred_language" name="preferred_language" class="form-control">
        <option
                @if(($user->preferred_language ?? old('preferred_language')) == "en")
                selected
                @endif
                value="en"
        >
            en
        </option>
        <option
                @if(($user->preferred_language ?? old('preferred_language')) == "nl")
                selected
                @endif
                value="nl"
        >
            nl
        </option>

    </select>
</div>
<div class="form-group">
    <label for="external_user">{{__('auth.external_user')}}</label>
    <select id="external_user" name="external_user" class="form-control">
        <option
                @if(($user->external_user ?? old('external_user')) == true)
                selected
                @endif
                value="1"
        >
            {{__('app.bool.true')}}

        </option>
        <option
                @if(($user->external_user ?? old('external_user')) == false)
                selected
                @endif
                value="0"
        >
            {{__('app.bool.false')}}

        </option>

    </select>
</div>
<div class="form-group">
    <label for="roles">{{__('app.Role')}}</label>
    <select id="roles" name="role_id" class="form-control">
        @foreach(\Spatie\Permission\Models\Role::all()->reverse() as $role)
            <option
                    @if(($user->role->id ?? old('role_id')) == $role->id)
                    selected
                    @endif
                    value="{{$role->id}}"
            >
                {{$role->name}}
            </option>
        @endforeach
    </select>
</div>
@include('components.backbutton')
<input class="btn btn-outline-primary float-right" type="submit" value="Submit">
