<div class="modal fade" id="album-create" tabindex="-1" role="dialog" aria-labelledby="album-create-ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    {{__('app.action.Create')}} {{__('app.album')}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <form method="POST" action="{{ route('album.store') }}">
                    @include('pages.album.fields', ['parent_id' => $parent_id, 'album' => null])
                </form>
            </div>
        </div>
    </div>
</div>
<a class="btn btn-outline-primary js-tooltip-enabled"
   data-toggle="modal"
   data-target="#album-create"
   data-toggle="tooltip"
   title="{{__('app.action.Create')}} {{__('app.sub')}} {{__('app.album')}}"
   data-original-title="{{__('app.action.Create')}} {{__('app.sub')}} {{__('app.album')}}"
>
    {{__('app.action.Create')}} {{__('app.sub')}} {{__('app.album')}}
</a>
