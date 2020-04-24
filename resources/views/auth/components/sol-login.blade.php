@if (config('auth.useSol'))
    <div class="card">
        <div class="card-header"><h1 class="card-title mt-1">{{ __('auth.login') }} {{__('auth.sol.trough')}}</h1></div>

        <div class="card-body">
            <form method="POST" action="{{ route('login.snl') }}">
                @csrf
                <div class="form-group row">
                    <label for="sol-user" class="col-md-4 col-form-label text-md-right">{{__('auth.sol.username')}}</label>

                    <div class="col-md-6">
                        <input id="sol-user"
                               class="form-control @error('sol-user') is-invalid @enderror"
                               name="sol-user"
                               value="{{ old('sol-user') }}"
                               required
                               autocomplete="email"
                               autofocus
                        >

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                {{--                            <div class="form-group row">--}}
                {{--                                <div class="col-md-6 offset-md-4">--}}
                {{--                                    <div class="form-check">--}}
                {{--                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}

                {{--                                        <label class="form-check-label" for="remember">--}}
                {{--                                            {{ __('auth.remember me') }}--}}
                {{--                                        </label>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('auth.login') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif
