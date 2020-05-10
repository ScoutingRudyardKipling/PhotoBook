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
<a data-toggle="modal" data-target="#remove-{{$item->id}}" class="btn btn-square btn-sm btn-outline-danger js-tooltip-enabled " data-toggle="tooltip" title="{{__('app.action.Delete')}}"
   data-original-title="{{__('app.action.Delete')}}" style="margin-bottom: 0;"
>
    <svg class="bi bi-x" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 010 .708l-7 7a.5.5 0 01-.708-.708l7-7a.5.5 0 01.708 0z" clip-rule="evenodd"/>
        <path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 000 .708l7 7a.5.5 0 00.708-.708l-7-7a.5.5 0 00-.708 0z" clip-rule="evenodd"/>
    </svg>
</a>
