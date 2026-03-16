@php
    $breadCrumbs = collect();
    $i = $album->parent;
    while (!empty($i)) {
        $breadCrumbs->prepend(['id' => $i->id, 'name' => $i->name]);
        $i = $i->parent;
    }
@endphp
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">{{ __('app.Dashboard') }}</a>
        </li>
        @foreach ($breadCrumbs as $crumb)
            <li class="breadcrumb-item">
                <a href="{{ route('album.show', [$crumb['id']]) }}">{{ $crumb['name'] }}</a>
            </li>
        @endforeach
        @if (!empty($showCurrent))
            <li class="breadcrumb-item active" aria-current="page">{{ $album['name'] }}</li>
        @endif
    </ol>
</nav>
