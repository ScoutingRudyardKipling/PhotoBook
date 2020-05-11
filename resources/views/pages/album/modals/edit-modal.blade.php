<div class="modal fade" id="album-edit" tabindex="-1" role="dialog" aria-labelledby="album-edit-ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    {{__('app.action.Edit')}} {{__('app.album')}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <form method="POST" action="{{ route('album.update' , $album->id) }}">
                        @method('PUT')
                        @include('pages.album.fields', ['album' => $album, 'parent_id' => $album->parent_id])
                    </form>
            </div>
        </div>
    </div>
</div>
<a class="btn btn-outline-secondary js-tooltip-enabled"
   data-toggle="modal"
   data-target="#album-edit"
   data-toggle="tooltip"
   title="{{__('app.action.Edit')}} {{__('app.album')}}"
   data-original-title="{{__('app.action.Edit')}} {{__('app.album')}}"
>
    {{__('app.action.Edit')}} {{__('app.album')}}
</a>
