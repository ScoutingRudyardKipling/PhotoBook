<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
            <div class="alert alert-{{ $msg }} alert-dismissible fadeIn mb-3" role="alert">{{ Session::get('alert-' . $msg) }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        @endif
    @endforeach
</div>