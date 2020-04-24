@canany(['Add Content','Edit Album','Add Album'])
    <div class="card mt-3">
        <div class="card-header">
            <h1 class="card-title">{{__('app.Actions')}}</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 align-content-end">
                    @if(!\Request::is('/'))
                        @can('Add Content')
                            <a class="btn btn-outline-primary" href="{{route('content.upload',['album' => $id])}}">
                                {{__('app.action.Upload')}} {{__('app.files')}}
                            </a>
                        @endcan
                        @can('Edit Album')
                            <a class="btn btn-outline-primary" href="{{route('album.edit', ['id'=>$id])}}">
                                {{__('app.action.Edit')}} {{__('app.album')}}
                            </a>
                        @endcan
                    @endif
                    @can('Add Album')
                        <a class="btn btn-outline-primary" href="{{route('album.create')}}">
                            {{__('app.action.Create')}} {{__('app.sub')}} {{__('app.album')}}
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endcanany
