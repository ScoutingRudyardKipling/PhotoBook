@php
    $id = $id ?? (isset($album) ? $album->id : 0);
@endphp
@canany(['Add Content', 'Edit Album', 'Add Album'])
    {{-- Modals must live outside .actions-bar to escape its backdrop-filter stacking context --}}
    @if (!\Request::is('/'))
        @can('Edit Album')
            @include('pages.album.modals.edit-modal')
        @endcan
        @can('Delete Album')
            @include('components.cruds.delete-modal', [
                'item'       => \App\Models\Album::find($id),
                'route'      => route('album.destroy', $id),
                'modalTitle' => __('app.Album') . ' ' . __('app.action.deleting'),
                'modalBody'  => __('validation.are-you-sure', ['Attribute' => __('app.album'), 'value' => \App\Models\Album::find($id)->name]),
            ])
        @endcan
    @endif
    @can('Add Album')
        @include('pages.album.modals.create-modal', ['parent_id' => $id ?? 0])
    @endcan

    <div class="actions-bar">
        @if (!\Request::is('/'))
            @can('Edit Album')
                <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#album-edit"
                >
                    {{ __('app.action.Edit') }} {{ __('app.album') }}
                </button>
            @endcan
            @can('Delete Album')
                <button type="button" class="btn btn-outline-danger btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#remove-{{ $id }}"
                >
                    {{ __('app.action.Delete') }} {{ __('app.album') }}
                </button>
            @endcan
            @can('Add Content')
                <a class="btn btn-outline-primary btn-sm" href="{{ route('content.upload', ['album' => $id]) }}">
                    {{ __('app.action.Upload') }} {{ __('app.files') }}
                </a>
            @endcan
        @endif
        @can('Add Album')
            <button type="button" class="btn btn-outline-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#album-create"
            >
                {{ __('app.action.Create') }} {{ __('app.sub') }} {{ __('app.album') }}
            </button>
        @endcan
        @php
            $showSlideshow = false;
            $slideshowUrl = route('photos.all');
            if (isset($album)) {
                $showSlideshow = $album->hasContentRecursive();
                $slideshowUrl = route('album.photos', ['album' => $album->id]);
            }
        @endphp
        @if ($showSlideshow)
            <button type="button" id="start-slideshow" class="btn btn-outline-success btn-sm" data-url="{{ $slideshowUrl }}">
                {{ __('app.action.Slideshow') }}
            </button>
        @endif
    </div>
@endcanany
