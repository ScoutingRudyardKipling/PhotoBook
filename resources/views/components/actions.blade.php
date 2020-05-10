@canany(['Add Content','Edit Album','Add Album'])
    <div class="card mt-3">
        <div class="card-header">
            <h1 class="card-title">{{__('app.Actions')}}</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 align-content-end">
                    @if(!\Request::is('/'))
                        @can('Edit Album')
                            <a class="btn btn-outline-secondary" href="{{route('album.edit', ['id'=>$id])}}">
                                {{__('app.action.Edit')}} {{__('app.album')}}
                            </a>
                        @endcan
                        @can('Delete Album')
                            @include('components.cruds.delete-modal', [
                                                'item' => \App\Models\Album::find($id),
                                                'route' => route('album.destroy', $id),
                                                'modalTitle' => __('app.Album') . ' ' . __('app.action.deleting'),
                                                'modalBody' => __('validation.are-you-sure', ['Attribute' => __('app.album'), 'value' => \App\Models\Album::find($id)->name])
                                            ])
                            <a class="btn btn-outline-warning js-tooltip-enabled"
                               data-toggle="modal"
                               data-target="#remove-{{$id}}"
                               data-toggle="tooltip"
                               title="{{__('app.action.Delete')}}"
                               data-original-title="{{__('app.action.Delete')}}"
                            >
                                {{__('app.action.Delete')}} {{__('app.album')}}
                            </a>
                        @endcan
                        @can('Add Content')
                            <a class="btn btn-outline-primary" href="{{route('content.upload',['album' => $id])}}">
                                {{__('app.action.Upload')}} {{__('app.files')}}
                            </a>
                        @endcan
                    @endif
                    @can('Add Album')
                        <a class="btn btn-outline-primary" href="{{route('album.create', ['parent' => $id ?? 0])}}">
                            {{__('app.action.Create')}} {{__('app.sub')}} {{__('app.album')}}
                        </a>
                    @endcan

                </div>
            </div>
        </div>
    </div>
@endcanany
