<div class="col-6 col-md-4 col-lg-3 mt-4 d-flex">


    <div class="card">
        @can('Delete Content')
            <button type="button" class="btn btn-danger bmd-btn-icon" data-toggle="modal" data-target="#remove-{{$content->id}}">
                <svg class="bi bi-x" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 010 .708l-7 7a.5.5 0 01-.708-.708l7-7a.5.5 0 01.708 0z" clip-rule="evenodd"/>
                    <path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 000 .708l7 7a.5.5 0 00.708-.708l-7-7a.5.5 0 00-.708 0z" clip-rule="evenodd"/>
                </svg>
            </button>
            @include('components.cruds.delete-modal',
                [
                     'item' => $content,
                     'route' => route('content.destroy', $content->id),
                     'modalTitle' => __('app.Content') . ' ' . __('app.action.deleting'),
                     'modalBody' => __('validation.are-you-sure', ['Attribute' => __('app.content'), 'value' => $content->name])
                 ]
             )
        @endcan
        <a class="img-link img-lightbox mx-auto d-block" href="{{ $content->getUrl() }}">
            <img class="card-img img-fluid" src="{{ $content->getUrl('thumb')}}" alt="{{$content->name}}"/>
            <div class="card-header">{{$content->name}}</div>
        </a>
    </div>


</div>
