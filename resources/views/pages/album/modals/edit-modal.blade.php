<div class="modal fade" id="album-edit" tabindex="-1" aria-labelledby="album-edit-ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="album-edit-ModalLabel">
                    {{ __('app.action.Edit') }} {{ __('app.album') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <form method="POST" action="{{ route('album.update', $album->id) }}">
                    @method('PUT')
                    @include('pages.album.fields', ['album' => $album, 'parent_id' => $album->parent_id, 'modal' => true])
                </form>
            </div>
        </div>
    </div>
</div>
