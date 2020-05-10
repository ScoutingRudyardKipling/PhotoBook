<div class="modal fade" id="remove-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $item->id }}-ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $item->id }}">{{ $modalTitle }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                {!! $modalBody !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-square btn-danger mr-auto" data-dismiss="modal">{{__('app.action.Cancel')}}</button>
                <form method="POST" action="{{ $route }}">
                    @method('delete')
                    @csrf
                    <input class="btn btn-outline-danger submit-button with-spinner" type="submit" value="{{__('app.action.Delete')}}">
                </form>
            </div>
        </div>
    </div>
</div>
