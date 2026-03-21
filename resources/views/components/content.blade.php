<div class="col-6 col-md-4 col-lg-3 mt-4">
    <div class="photo-card">
        <div class="photo-thumb-wrapper">
            <a class="img-link glightbox" href="{{ $content->getUrl() }}" data-type="image" data-gallery="album-gallery" data-title="{{ $content->name }}">
                <img src="{{ $content->getUrl('thumb') }}" alt="{{ $content->name }}"/>
            </a>
            @can('Delete Content')
                <div class="photo-delete-btn">
                    <button type="button"
                            class="btn btn-danger btn-sm rounded-circle"
                            data-bs-toggle="modal"
                            data-bs-target="#remove-{{ $content->id }}"
                            style="width: 28px; height: 28px; padding: 0; line-height: 1; font-size: 1.1rem;"
                            title="{{ __('app.action.Delete') }}"
                    >&times;</button>
                </div>
            @endcan
        </div>
        <div class="photo-name">{{ $content->name }}</div>
    </div>
    @can('Delete Content')
        @include('components.cruds.delete-modal', [
            'item'       => $content,
            'route'      => route('content.destroy', $content->id),
            'modalTitle' => __('app.Content') . ' ' . __('app.action.deleting'),
            'modalBody'  => __('validation.are-you-sure', ['Attribute' => __('app.content'), 'value' => $content->name]),
        ])
    @endcan
</div>
