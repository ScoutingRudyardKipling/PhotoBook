<div class="modal fade" id="album-create" tabindex="-1" aria-labelledby="album-create-ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="album-create-ModalLabel">
                    {{ __('app.action.Create') }} {{ __('app.album') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <form method="POST" action="{{ route('album.store') }}">
                    @include('pages.album.fields', ['parent_id' => $parent_id, 'album' => null, 'modal' => true])
                </form>
            </div>
        </div>
    </div>
</div>
