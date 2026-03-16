<div class="modal fade" id="remove-{{ $item->id }}" tabindex="-1" aria-labelledby="{{ $item->id }}-ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $item->id }}-ModalLabel">{{ $modalTitle }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                {!! $modalBody !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">{{ __('app.action.Cancel') }}</button>
                <form method="POST" action="{{ $route }}">
                    @method('delete')
                    @csrf
                    <input class="btn btn-danger" type="submit" value="{{ __('app.action.Delete') }}">
                </form>
            </div>
        </div>
    </div>
</div>
